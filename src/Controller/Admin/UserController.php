<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\UserInvite;
use App\Form\UserInviteType;
use App\Form\UserType;
use App\Repository\UserInviteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/invite', name: 'app_admin_user_invite', methods: ['GET', 'POST'])]
    public function new(Request $request, UserInviteRepository $inviteRepository): Response
    {
        $invite = new UserInvite();
        $form = $this->createForm(UserInviteType::class, $invite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$invite->setCreated(new \DateTime());
			$invite->setCode($this->generateCode());

            $inviteRepository->add($invite, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

		return $this->render('admin/user/invite.html.twig', [
			'invite' => $invite,
			'form' => $form->createView(),
		]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        if($user->isGranted('ROLE_SUPER_ADMIN')){
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$user->setRoles([$form->get('role')->getData()]);
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if($user->isGranted('ROLE_SUPER_ADMIN')){
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

	private function generateCode() : string {
		return uniqid('invite_', true);
	}
}
