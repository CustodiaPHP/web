<?php

namespace App\Command;

use App\Entity\Service;
use App\Entity\ServiceLog;
use App\Repository\ServiceLogRepository;
use App\Repository\ServiceRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCommand extends Command
{
    protected static $defaultName = 'app:fetch';
    protected static $defaultDescription = 'Command for fetching the status of the services';

    private array $STATUSES = [
        0 => '%s is currently not reachable',
        1 => '%s is Operational again',
        2 => '%s is currently having perfomance issues',
        3 => '%s is currently having a minor outage',
        4 => '%s is currently having a major outage'
    ];

    private float $RESPONSE_MULTIPLIER = 1.75;

    private ManagerRegistry $manager;
    private Messaging $firebase;

    private ServiceRepository $serviceRepository;
    private ServiceLogRepository $logRepository;

    private string $notificationTag = "isancozocktonline";

    public function __construct(ServiceRepository $serviceRepository, ServiceLogRepository $logRepository, ManagerRegistry $manager, Messaging $messaging, string $name = null)
    {
        parent::__construct($name);

        $this->manager = $manager;
        $this->firebase = $messaging;

        $this->serviceRepository = $serviceRepository;
        $this->logRepository = $logRepository;
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Client([
            'timeout' => 15.0
        ]);

        $services = $this->serviceRepository->findAll();
        foreach ($services as $service){
            if($service->getType() == 0)
            {
                $this->pingWebsite($client, $service, $output);
            }
            elseif ($service->getType() == 1)
            {
                $this->pingServer($service, $output);
            }
        }

        $this->manager->getManager()->flush();
        $this->clearOldLogs();

        return Command::SUCCESS;
    }

    private function pingServer(Service $service, OutputInterface $output){
        $log = new ServiceLog();
        $log->setService($service);
        $address = explode(':', $service->getAddress());

        $host = $address[0];
        $port = $address[1] ?? 80;

        $startTime = round(microtime(true) * 1000);
        $sock = @fsockopen($host, $port, $error_code, $error, 15);
        if(!$sock){
            $endTime = round(microtime(true) * 1000);
            $responseTime = $endTime - $startTime;
            if($service->getCurrentStatus() == 3){
                $log->setStatus(4);
            }else{
                $log->setStatus(3);
            }
        }

        if($sock){
            $endTime = round(microtime(true) * 1000);
            $responseTime = $endTime - $startTime;
            fclose($sock);
            if(($responseTime /1000 ) > ($this->getAverage($service) / 1000) * $this->RESPONSE_MULTIPLIER){
                $log->setStatus(2);
            }else{
                $log->setStatus(1);
            }
        }

        $log->setResponseTime($responseTime);
        $log->setTimestamp(new \DateTime());
        $this->manager->getManager()->persist($log);

        $this->updateStatus($service, $log, $output);
    }

    private function pingWebsite(Client $client, Service $service, OutputInterface $output){
        $log = new ServiceLog();
        $log->setService($service);

        $startTime = round(microtime(true) * 1000);
        try {
            $response = $client->get($service->getAddress());
            if($response->getStatusCode() != 200){
                if($service->getCurrentStatus() == 3){
                    $log->setStatus(4);
                }else{
                    $log->setStatus(3);
                }
            }
            $endTime = round(microtime(true) * 1000);
            $responseTime = $endTime - $startTime;
            if(($responseTime / 1000) > ($this->getAverage($service) / 1000) * $this->RESPONSE_MULTIPLIER){
                $log->setStatus(2);
            }else{
                $log->setStatus(1);
            }
        } catch (GuzzleException $e) {
            $endTime = round(microtime(true) * 1000);
            $responseTime = $endTime - $startTime;
            $log->setStatus(0);
        }

        $log->setResponseTime($responseTime);
        $log->setTimestamp(new \DateTime());
        $this->manager->getManager()->persist($log);

        $this->updateStatus($service, $log, $output);
    }

    private function updateStatus(Service $service, ServiceLog $latestLog, OutputInterface $output)
    {
        if($latestLog->getStatus() > 1)
        {
          if($service->getServiceLogs()->last()->getStatus() == $latestLog->getStatus() || $latestLog->getStatus() == 4)
          {
            $this->notify($service, $latestLog, $output);
            $service->setCurrentStatus($latestLog->getStatus());
          }
        }
        else
        {
          $this->notify($service, $latestLog, $output);
          $service->setCurrentStatus($latestLog->getStatus());
        }
    }

    private function notify(Service $service, ServiceLog $log, OutputInterface $output)
    {
        if($service->getCurrentStatus() == $log->getStatus()){
          return;
        }

        $message = CloudMessage::withTarget('topic', strtolower($this->notificationTag))
            ->withNotification(Notification::create($service->getName().' status changed',
                sprintf($this->STATUSES[$log->getStatus()], $service->getName())));

        try {
            $this->firebase->send($message);
        } catch (MessagingException | FirebaseException $e) {
            $output->write($e->getMessage());
        }
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

    private function getAverage(Service $service) : float {
        $logs = $service->getServiceLogs();
        $value = 0;

        foreach ($logs as $log){
            $value += $log->getResponseTime();
        }

        return $value / count($logs);
    }

}
