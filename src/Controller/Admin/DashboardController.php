<?php

namespace App\Controller\Admin;

use App\Repository\ServiceRepository;
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
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }
}
