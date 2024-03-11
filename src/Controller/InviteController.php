<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInvite;
use App\Form\JoinType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class InviteController extends AbstractController
{
    #[Route('/join/{code}', name: 'app_invite_join')]
    public function index(Request $request, UserInvite $invite, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
		$form = $this->createForm(JoinType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$plainPassword = $form->get('password')->getData();
			$plainPasswordRepeat = $form->get('repeatPassword')->getData();

			if($plainPassword !== $plainPasswordRepeat){
				$this->addFlash('error', 'Passwords do not match');
				return $this->redirectToRoute('app_invite_join', ['code' => $invite->getCode()]);
			}

			$user = new User();

			$user->setPassword($hasher->hashPassword($user, $plainPassword));
			$user->setEmail($invite->getEmail());
			$user->setUsername($invite->getUsername());
			$user->setRoles([$invite->getRole()]);

			$userRepository->add($user, true);

			return $this->redirectToRoute('app_login');
		}

        return $this->render('invite/index.html.twig', [
			'invite' => $invite,
			'form' => $form->createView(),
        ]);
    }
}
