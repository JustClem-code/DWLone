<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PickingController extends AbstractController
{
    #[Route('/warehouse/picking', name: 'app_picking')]
    public function index(): Response
    {
        return $this->render('picking/index.html.twig', [
            'controller_name' => 'PickingController',
        ]);
    }
}
