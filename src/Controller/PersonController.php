<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\NewPersonType;
use App\Repository\PersonRepository;
use App\Service\Person\PersonManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $not_assigned_people = $personRepository->findBy(
            ['grave' => null],
            ['id' => 'DESC']
        );

        if ($form_add_person->isSubmitted() && $form_add_person->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_MANAGER');
            $one = $form_add_person->getData();
            $grave->setEditedBy($this->getUser()->getUsername());
            $one->setCreated(new DateTime());
            $one->setGrave($grave);
            $personRepository->save($one, true);
            $this->addFlash('success', 'Osoba dodana do pochówku!');
            $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/index.html.twig', [
            'grave' => $grave,
            'person' => $person,
            'people' => $not_assigned_people,
            'last_uri' => $lastUri,
            'form_add_person' => $form_add_person
        ]);
    }

    #[Route('/person/api/update/{person}', name: 'app_api_person_update')]
    public function api_update_person(Request $request, Person $person, PersonRepository $personRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if ($request->isMethod('put')) {
            $external_request = json_decode($request->getContent(), true);
            if ($external_request['clearGrave']) {
                $person->setGrave(null);
                $personRepository->save($person, true);
                $data = true;
                $this->addFlash('success', 'Osoba została usunięta z grobu.');
            } else {
                $data = false;
                $this->addFlash('failed', 'Akcja zakończona niepowodzeniem!');
            }

        } else {
            $data = false;
        }
        return new JsonResponse($data);
    }

    #[Route('/person/api/get/{type}', name: 'app_api_person_get')]
    public function api_get_person(Request $request, PersonManager $personManager, string $type): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if ($request->isMethod('get')) {
            $data = match ($type) {
                'not_assigned' => $personManager->getNotAssignedPeople(),
                default => true,
            };
        } else {
            $data = false;
        }
        return new JsonResponse($data);
    }
}
