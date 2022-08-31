<?php

namespace App\Controller\Admin;

use App\Repository\IncidentRepository;
use App\Repository\ServiceLogRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/")
     * @Route("/dashboard", name="app_admin_dashboard")
     */
    public function index(ServiceRepository $serviceRepository, ServiceLogRepository $logRepository,
                          UserRepository $userRepository, IncidentRepository $incidentRepository): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'logs' => $logRepository->count([]),
            'users' => $userRepository->count([]),
            'incidents' => $incidentRepository->count([]),
            'services' => $serviceRepository->findAll(),
        ]);
    }
}
