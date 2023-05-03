<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\IncidentRepository;
use App\Repository\ServiceGroupRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
	#[Route('/', name: 'app_welcome')]
    public function index(ServiceGroupRepository $repository, ServiceRepository $serviceRepository, IncidentRepository $incidents): Response
    {
        return $this->render('main/index.html.twig', [
            'groups' => $repository->findBy([
                'public' => true
            ]),
            'services' => $serviceRepository,
            'incidents' => $incidents->findLastIncidents()
        ]);
    }

	#[Route('/service/{name}', name: 'app_service_view')]
    public function service(Service $service) : Response
    {
        if(!$service->isPublic() && !$this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('service/index.html.twig', [
            'service' => $service,
            'logs' => $service->getServiceLogs()
        ]);
    }
}
