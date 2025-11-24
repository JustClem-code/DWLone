<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnloadingController extends AbstractController
{
    #[Route('/warehouse/unloading', name: 'app_unloading')]
    public function index(): Response
    {
        return $this->render('unloading/index.html.twig', [
            'controller_name' => 'UnloadingController',
        ]);
    }
}
