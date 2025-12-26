<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StowController extends AbstractController
{
    #[Route('/warehouse/stow', name: 'app_stow')]
    public function index(): Response
    {
        return $this->render('stow/index.html.twig', [
            'controller_name' => 'StowController',
        ]);
    }
}

