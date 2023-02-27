<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/notifications')]
class NotificationController extends AbstractController
{
    #[Route('/', name: 'api_notification')]
    public function poll(): Response
    {
        $test = [
            [
                'service' => 'Blog',
                'status' => 3
            ],
            [
                'service' => 'Website',
                'status' => 2
            ],
        ];

        return $this->json($test);
    }
}
