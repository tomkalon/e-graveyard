<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\NewPersonType;
use App\Repository\PersonRepository;
use App\Service\EditUpdate\EditUpdate;
use App\Service\Form\FormDataSort;
use App\Service\Person\PersonManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/person/{person<\d+>}', name: 'app_person', priority: 1)]
    public function index(
        Person $person,
        PersonRepository $personRepository,
        EditUpdate $editUpdate,
        Request $request
    ): Response {
        // entity
        $grave = $person->getGrave();
        $new_person = new Person();

        // form
        $form_add_person = $this->createForm(NewPersonType::class, $new_person);
        $form_add_person->handleRequest($request);

        // query
        $not_assigned_people = $personRepository->findBy(
            ['grave' => null],
            ['id' => 'DESC']
        );

        // form handler
        if ($form_add_person->isSubmitted() && $form_add_person->isValid()) {
            // security
            $this->denyAccessUnlessGranted('ROLE_MANAGER');

            // save data
            $new_person = $form_add_person->getData();
            $new_person->setGrave($grave);
            $editUpdate->updateBoth($grave, $new_person, $this->getUser(), 'person');

            // flash message
            $this->addFlash('success', 'Osoba dodana do pochówku!');

            // redirection
            $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/index.html.twig', [
            'grave' => $grave,
            'person' => $person,
            'people' => $not_assigned_people,
            'last_uri' => $request->headers->get('referer'),
            'form_add_person' => $form_add_person
        ]);
    }

    #[Route('/person/manage/not_assigned{page<\d+>}', name: 'app_person_not_assigned')]
    public function notAssigned(
        PersonRepository $personRepository,
        Request $request,
        FormDataSort $dataSort,
        int $page = 0
    ): Response {
        // session
        $session = $request->getSession();
        $limit = $dataSort->getLimit($session, $request);
        $sort = $dataSort->getPersonSort($session, $request);

        // form
        $form = $dataSort->getPersonFormSort($this->createFormBuilder(), $limit, $sort);

        // query
        $search_result = $personRepository->findUnassigned($sort);

        return $this->render('main/search/not_assigned.html.twig', [
            'search_result' => $search_result,
            'form' => $form,
            'page' => $page,
            'limit' => $limit,
            'source' => 'person',
            'last_uri' => $request->headers->get('referer'),
        ]);
    }

    #[Route('/person/manage/add', name: 'app_person_add', priority: 5)]
    public function addPerson(
        Request $request,
        EditUpdate $editUpdate
    ): Response {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // entity
        $person = new Person();

        // form
        $form = $this->createForm(NewPersonType::class, $person);
        $form->handleRequest($request);

        // translation


        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();

            // save data
            $editUpdate->updateOne($person, $this->getUser(), true);

            // flash message
            $this->addFlash('success', 'Osoba została dodana do bazy danych.');

            // redirection
            return $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/add.html.twig', [
            'form' => $form,
            'last_uri' => $request->headers->get('referer'),
        ]);
    }

    #[Route('/person/manage/edit/{person<\d+>}', name: 'app_person_edit')]
    public function update(Person $person, EditUpdate $editUpdate, Request $request): Response
    {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // form
        $form = $this->createForm(NewPersonType::class, $person);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();

            // save data
            $editUpdate->updateOne($person, $this->getUser(), false);

            // flash message
            $this->addFlash('success', 'Edycja zakończona powodzeniem.');

            // redirection
            $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('person/edit.html.twig', [
            'close' => $this->generateUrl('app_person', [
                'person' => $person->getId()
            ]),
            'person' => $person,
            'last_uri' => $request->headers->get('referer'),
            'form' => $form
        ]);
    }

    #[Route('/person/manage/remove/{id<\d+>}', name: 'app_person_remove')]
    public function remove(Request $request, int $id, PersonRepository $personRepository): Response
    {
        // entity
        $person = $personRepository->find($id);

        if ($person !== null) {
            // save data
            $personRepository->remove($person, true);

            // flash message
            $this->addFlash('success', 'Osoba została usunięta!');

            // redirection
            return $this->redirectToRoute('app_search');
        } else {
            // flash message
            $this->addFlash('failed', 'Usunięcie grobu zakończone niepowodzeniem!');

            // redirection
            return $this->redirect($request->headers->get('referer'));
        }
    }

    #[Route('/person/api/remove/{person<\d+>}', name: 'app_api_person_remove')]
    public function apiRemove(
        Person $person,
        PersonRepository $personRepository,
        Request $request
    ): Response {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if ($request->isMethod('delete')) {
            // REMOVE DATA
            $personRepository->remove($person, true);

            // flash message
            $this->addFlash('success', 'Osoba została usunięta.');

            // api return data
            $data = $this->generateUrl('app_search', [
                'page' => 0
            ]);
        } else {
            // flash message
            $this->addFlash('failed', 'Akcja zakończona niepowodzeniem!');

            // api return data
            $data = false;
        }
        return new JsonResponse($data);
    }

    #[Route('/person/api/update/{person<\d+>}', name: 'app_api_person_update')]
    public function apiUpdate(
        Request $request,
        Person $person,
        EditUpdate $editUpdate
    ): Response {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // api handler
        if ($request->isMethod('put')) {
            $external_request = json_decode($request->getContent(), true);
            if ($external_request['clearGrave']) {
                $grave = $person->getGrave();
                $person->setGrave(null);
                $editUpdate->updateBoth($grave, $person, $this->getUser(), false);
                $data = true;
                $this->addFlash('success', 'Osoba została odpięta od grobu.');
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
    public function apiGet(
        Request $request,
        PersonManager $personManager,
        string $type
    ): Response {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // api handler
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
