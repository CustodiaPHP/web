<?php

namespace App\Command;

use App\Entity\Service;
use App\Entity\ServiceLog;
use App\Repository\ServiceLogRepository;
use App\Repository\ServiceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCommand extends Command
{
    protected static $defaultName = 'app:fetch';
    protected static $defaultDescription = 'Command for fetching the status of the services';

    private ManagerRegistry $manager;
    private ServiceRepository $serviceRepository;
    private ServiceLogRepository $logRepository;

    public function __construct(ServiceRepository $serviceRepository, ServiceLogRepository $logRepository, ManagerRegistry $manager, string $name = null)
    {
        parent::__construct($name);

        $this->manager = $manager;
        $this->serviceRepository = $serviceRepository;
        $this->logRepository = $logRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Client([
            'timeout' => 15.0
        ]);

        $services = $this->serviceRepository->findAll();
        foreach ($services as $service){
            if($service->getType() == 0)
            {
                $this->pingWebsite($client, $service);
            }
            elseif ($service->getType() == 1)
            {
                $this->pingServer($service);
            }
        }

        $this->manager->getManager()->flush();
        $this->clearOldLogs();

        return Command::SUCCESS;
    }

    private function pingServer(Service $service){
        $log = new ServiceLog();
        $log->setService($service);
        $address = explode(':', $service->getAdress());

        $host = $address[0];
        $port = $address[1] ?? 80;

        $startTime = round(microtime(true) * 1000);
        $sock = @fsockopen($host, $port, $error_code, $error, 15);

        if(!$sock){
            $endTime = round(microtime(true) * 1000);
            if($service->getCurrentStatus() == 3){
                $log->setStatus(4);
            }else{
                $log->setStatus(3);
            }
        }

        if($sock){
            $endTime = round(microtime(true) * 1000);
            fclose($sock);
            if(($endTime - $startTime)/1000 > 5.0){
                $log->setStatus(2);
            }else{
                $log->setStatus(1);
            }
        }

        $log->setResponseTime($endTime-$startTime);
        $log->setTimestamp(new \DateTime());
        $service->setCurrentStatus($log->getStatus());

        $this->manager->getManager()->persist($log);
    }

    private function pingWebsite(Client $client, Service $service){
        $log = new ServiceLog();
        $log->setService($service);

        $startTime = round(microtime(true) * 1000);
        try {
            $response = $client->get($service->getAdress());
            if($response->getStatusCode() != 200){
                if($service->getCurrentStatus() == 3){
                    $log->setStatus(4);
                }else{
                    $log->setStatus(3);
                }
            }
            $endTime = round(microtime(true) * 1000);
            if(($endTime - $startTime)/1000 > 5.0){
                $log->setStatus(2);
            }else{
                $log->setStatus(1);
            }
        } catch (GuzzleException $e) {
            $endTime = round(microtime(true) * 1000);
            $log->setStatus(0);
        }

        $log->setResponseTime($endTime-$startTime);
        $log->setTimestamp(new \DateTime());
        $service->setCurrentStatus($log->getStatus());

        $this->manager->getManager()->persist($log);
    }

    private function clearOldLogs(){
        $currentDate = new DateTime();
        $toOld = $currentDate->sub(new \DateInterval('P2W'));

        $logs = $this->logRepository->findOldLogs($toOld);

        if(count($logs) > 0)
        {
            foreach ($logs as $log){
                $this->manager->getManager()->remove($log);
            }
            $this->manager->getManager()->flush();
        }

    }
}
