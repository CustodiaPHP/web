<?php

namespace App\Controller\API;

use App\Entity\Service;
use App\Entity\ServiceLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/logs')]
class LogController extends AbstractController
{
	#[Route('/{id}', name: 'api_service_logs')]
    public function search_by_service(Service $service): Response
    {
        $filter = function (ServiceLog $log){
            $interval = date_diff($log->getTimestamp(), new \DateTime());
            return $interval->days < 1 and $interval->h <= 12;
        };

        $filteredLogs = $service->getServiceLogs()->filter($filter);
        $logs = array();
        foreach ($filteredLogs as $log)
        {
            $logs[$log->getTimestamp()->format('H:i')] = $log->getResponseTime()/1000;
        }


        $data = array(
            "name" => $service->getName(),
            "times" => $logs
        );
        return new JsonResponse($data);
    }
}
