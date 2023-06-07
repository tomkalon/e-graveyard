<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    #[Route('/manager', name: 'app_manager')]
    public function index(Request $request): Response
    {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        return $this->render('manager/index.html.twig');
    }
}
