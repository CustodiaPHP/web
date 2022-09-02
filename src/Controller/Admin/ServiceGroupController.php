<?php

namespace App\Controller\Admin;

use App\Entity\ServiceGroup;
use App\Form\ServiceGroupType;
use App\Repository\ServiceGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/service-group')]
class ServiceGroupController extends AbstractController
{
    #[Route('/', name: 'app_admin_service_group_index', methods: ['GET'])]
    public function index(ServiceGroupRepository $serviceGroupRepository): Response
    {
        return $this->render('admin/service_group/index.html.twig', [
            'service_groups' => $serviceGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_service_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceGroupRepository $serviceGroupRepository): Response
    {
        $serviceGroup = new ServiceGroup();
        $form = $this->createForm(ServiceGroupType::class, $serviceGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceGroupRepository->add($serviceGroup, true);

            return $this->redirectToRoute('app_admin_service_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/service_group/new.html.twig', [
            'service_group' => $serviceGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_service_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceGroup $serviceGroup, ServiceGroupRepository $serviceGroupRepository): Response
    {
        $form = $this->createForm(ServiceGroupType::class, $serviceGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceGroupRepository->add($serviceGroup, true);

            return $this->redirectToRoute('app_admin_service_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/service_group/edit.html.twig', [
            'service_group' => $serviceGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_service_group_delete', methods: ['POST'])]
    public function delete(Request $request, ServiceGroup $serviceGroup, ServiceGroupRepository $serviceGroupRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serviceGroup->getId(), $request->request->get('_token'))) {
            $serviceGroupRepository->remove($serviceGroup, true);
        }

        return $this->redirectToRoute('app_admin_service_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
