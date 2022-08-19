<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\ServiceLog;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/status", name="api_status")
     */
    public function status(ServiceRepository $serviceRepository, Request $request): Response
    {
        if($request->headers->has("Accept") && $request->headers->get("Accept") == "application/json"){
            $current_status = 1;
            if($serviceRepository->findAllByState(4) > 0){
                $current_status = 4;
            }else if($serviceRepository->findAllByState(3) > 0){
                $current_status = 3;
            }else if($serviceRepository->findAllByState(2) > 0){
                $current_status = 2;
            }

            return new JsonResponse([
                'status' => $current_status
            ]);
        }
        return $this->render('main/status_overview.html.twig', ['services' => $serviceRepository]);
    }

    /**
     * @Route("/status/{id}", name="api_status_service")
     */
    public function service_status(Service $service, Request $request): Response
    {
        if($request->headers->has("Accept") && $request->headers->get("Accept") == "application/json"){
            return new JsonResponse([
                'service' => $service->getName(),
                'status' => $service->getCurrentStatus()
            ]);
        }
        return $this->render('main/status_badge.html.twig', ['service' => $service]);
    }

    /**
     * @Route("/status/{id}/head", name="api_service_head")
     */
    public function service_status_head(Service $service): Response
    {
        return $this->render('service/service_head_badge.html.twig', ['service' => $service]);
    }

    /**
     * @Route("/logs/{id}", name="api_service_logs")
     */
    public function service_logs(Service $service): Response
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
