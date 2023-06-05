<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\NewPersonType;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use App\Service\Person\EditUpdate\EditUpdate;
use App\Service\Person\PersonManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/{person<\d+>}', name: 'app_person', priority: 1)]
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
            $user = $this->getUser()->getUsername();
            $grave->setEditedBy($user);
            $one->setEditedBy($user);
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

    #[Route('/person/not_assigned{page<\d+>}', name: 'app_person_not_assigned')]
    public function not_assigned(PersonRepository $personRepository, Request $request, int $page = 0): Response
    {
        // session
        $session = $request->getSession();
        $session->set('last_uri', $request->getUri());

        // matching search data
        $search_result = $personRepository->findBy(
            ['grave' => null],
            ['id' => 'DESC']
        );

        // query limit
        $limit = 15;

        return $this->render('main/search/result.html.twig', [
            'search_result' => $search_result,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    #[Route('/person/add', name: 'app_add_person', priority: 5)]
    public function add_person(Request $request, PersonRepository $personRepository, EditUpdate $editUpdate): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        $session = $request->getSession();
        $session->set('last_uri', $request->getUri());
        $person = new Person();

        $form = $this->createForm(NewPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $editUpdate->updateOne($person, $this->getUser(), true);
            $this->addFlash('success', 'Osoba została dodana do bazy danych.');

            return $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/person/edit/update/{person<\d+>}', name: 'app_person_edit')]
    public function update(Person $person, EditUpdate $editUpdate, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // session
        $session = $request->getSession();
        $lastUri = $session->get('last_uri');

        // form - add new Person
        $form = $this->createForm(NewPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $editUpdate->updateOne($person, $this->getUser(), false);
            $this->addFlash('success', 'Edycja zakończona powodzeniem.');
            $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/update.html.twig', [
            'person' => $person,
            'last_uri' => $lastUri,
            'form' => $form
        ]);
    }

    #[Route('/person/api/remove/{person<\d+>}', name: 'app_person_remove')]
    public function remove(Person $person, PersonRepository $personRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if ($request->isMethod('delete')) {

            // session
            $session = $request->getSession();
            $last_uri = $session->get('last_uri');

            // repository
            $personRepository->remove($person, true);

            // flash message
            $this->addFlash('success', 'Osoba została usunięta.');

            // api return data
            $last_uri ? $data = $last_uri : $this->generateUrl('app_search');

        } else {

            // flash message
            $this->addFlash('failed', 'Akcja zakończona niepowodzeniem!');

            // api return data
            $data = false;
        }
        return new JsonResponse($data);
    }

    #[Route('/person/api/update/{person<\d+>}', name: 'app_api_person_update')]
    public function api_update_person(Request $request, Person $person, PersonRepository $personRepository, GraveRepository $graveRepository,
                                      EditUpdate $editUpdate): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if ($request->isMethod('put')) {
            $external_request = json_decode($request->getContent(), true);
            if ($external_request['clearGrave']) {
                $grave = $person->getGrave();
                $person->setGrave(null);
                $editUpdate->updateBoth($grave, $person, $this->getUser(), false);
                $graveRepository->save($grave);
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
