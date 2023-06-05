<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Entity\Person;
use App\Form\NewGraveType;
use App\Form\NewPersonType;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use App\Service\Person\EditUpdate\EditUpdate;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManagerController extends AbstractController
{
    #[Route('/manager', name: 'app_manager')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        return $this->render('manager/index.html.twig');
    }
}
