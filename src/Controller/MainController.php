<?php

namespace App\Controller;

use App\Repository\IncidentRepository;
use App\Repository\ServiceGroupRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_welcome")
     */
    public function index(ServiceGroupRepository $repository,
                          ServiceRepository $serviceRepository,
                          IncidentRepository $incidents): Response
    {
        return $this->render('main/index.html.twig', [
            'groups' => $repository->findBy([
                'public' => true
            ]),
            'services' => $serviceRepository,
            'incidents' => $incidents->findLastIncidents()
        ]);
    }
}
