<?php

namespace App\Controller\API;

use App\Entity\Service;
use App\Entity\ServiceLog;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/status')]
class StatusController extends AbstractController
{
	#[Route('/', name: 'api_status', methods: ['GET'])]
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

	#[Route('/{id}', name: 'api_status_service')]
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

	#[Route('/{id}/head', name: 'api_service_head')]
    public function service_status_head(Service $service): Response
    {
        return $this->render('service/service_head_badge.html.twig', ['service' => $service]);
    }

}
