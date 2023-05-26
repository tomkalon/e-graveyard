<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraveyardController extends AbstractController
{
    #[Route('/graveyard', name: 'app_graveyard')]
    public function index(): Response
    {
        return $this->render('graveyard/index.html.twig', [
            'controller_name' => 'GraveyardController',
        ]);
    }
}
