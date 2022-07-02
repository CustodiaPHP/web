<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function status(ServiceRepository $serviceRepository): Response
    {
        return $this->render('main/status_overview.html.twig', ['services' => $serviceRepository]);
    }

    /**
     * @Route("/status/{id}", name="api_status_service")
     */
    public function service_status(Service $service): Response
    {
        return $this->render('main/status_badge.html.twig', ['service' => $service]);
    }
}
