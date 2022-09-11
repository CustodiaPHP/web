<?php

namespace App\Controller\API;

use App\Entity\Service;
use App\Repository\IncidentRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/incident")
 */
class IncidentController extends AbstractController
{

    /**
     * @Route("/list", name="api_incident_list")
     */
    public function list(IncidentRepository $incidentRepository): Response
    {
        $incidents = $incidentRepository->findLastIncidents(5);
        $response = [];

        foreach ($incidents as $incident)
        {
            $response[] = [
                'title' => $incident->getTitle(),
                'message' => $incident->getMessage(),
                'status' => $incident->getStatus(),
                'created' => $incident->getCreated()->format('M d, Y')
            ];
        }

        return new JsonResponse($response);
    }

}
