<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/{person<\d+>}', name: 'app_person')]
    public function index(Person $person, Request $request): Response
    {
        $session = $request->getSession();
        $lastUri = $session->get('last_uri');
        $grave = $person->getGrave();

        return $this->render('person/index.html.twig', [
            'grave' => $grave,
            'person' => $person,
            'last_uri' => $lastUri
        ]);
    }
}
