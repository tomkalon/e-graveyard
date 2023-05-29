<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraveController extends AbstractController
{
    #[Route('/grave', name: 'app_grave')]
    public function index(): Response
    {
        return $this->render('grave/index.html.twig', [
            'controller_name' => 'GraveController',
        ]);
    }
}
