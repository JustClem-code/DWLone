<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InductionController extends AbstractController
{
    #[Route('/warehouse/induction', name: 'app_induction')]
    public function index(): Response
    {
        return $this->render('induction/index.html.twig', [
            'controller_name' => 'InductionController',
        ]);
    }
}
