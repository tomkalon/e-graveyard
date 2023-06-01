<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\NewPersonType;
use App\Repository\PersonRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/{person<\d+>}', name: 'app_person')]
    public function index(Person $person, PersonRepository $personRepository, Request $request): Response
    {
        // session
        $session = $request->getSession();
        $lastUri = $session->get('last_uri');

        // grave Entity
        $grave = $person->getGrave();

        // form - add new Person
        $one = new Person();
        $form_add_person = $this->createForm(NewPersonType::class, $one);
        $form_add_person->handleRequest($request);

        if ($form_add_person->isSubmitted() && $form_add_person->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_MANAGER');
            $one = $form_add_person->getData();
            $grave->setEditedBy($this->getUser()->getUsername());
            $one->setCreated(new DateTime());
            $one->setGrave($grave);
            $personRepository->save($one, true);

            $this->addFlash('added', 'Osoba dodana do pochówku!');
            $request->request->remove('new_person');
            $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/index.html.twig', [
            'grave' => $grave,
            'person' => $person,
            'last_uri' => $lastUri,
            'form_add_person' => $form_add_person
        ]);
    }
}
