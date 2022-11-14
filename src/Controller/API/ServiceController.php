<?php

namespace App\Controller\API;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/service')]
class ServiceController extends AbstractController
{

	#[Route('/list', name: 'api_service_list')]
    public function list(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        $response = [];

        foreach ($services as $service)
        {
            $response[] = [
                'name' => $service->getName(),
                'status' => $service->getCurrentStatus(),
                'response_time' => $this->getAverage($service)
            ];
        }

        return new JsonResponse($response);
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
