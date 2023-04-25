<?php

namespace App\Controller\Admin;

use App\Entity\Incident;
use App\Form\IncidentType;
use App\Repository\IncidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/incident')]
class IncidentController extends AbstractController
{
    #[Route('/', name: 'app_admin_incident_index', methods: ['GET'])]
    public function index(IncidentRepository $incidentRepository): Response
    {
        return $this->render('admin/incident/index.html.twig', [
            'incidents' => array_reverse( $incidentRepository->findAll() ),
        ]);
    }

    #[Route('/new', name: 'app_admin_incident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IncidentRepository $incidentRepository): Response
    {
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incident->setCreated(new \DateTime());
            $incidentRepository->add($incident, true);

            return $this->redirectToRoute('app_admin_incident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/incident/new.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_incident_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Incident $incident, IncidentRepository $incidentRepository): Response
    {
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidentRepository->add($incident, true);

            return $this->redirectToRoute('app_admin_incident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/incident/edit.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_incident_delete', methods: ['POST'])]
    public function delete(Request $request, Incident $incident, IncidentRepository $incidentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$incident->getId(), $request->request->get('_token'))) {
            $incidentRepository->remove($incident, true);
        }

        return $this->redirectToRoute('app_admin_incident_index', [], Response::HTTP_SEE_OTHER);
    }
}
