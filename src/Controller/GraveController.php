<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Entity\Person;
use App\Form\NewGraveType;
use App\Form\NewPersonType;
use App\Repository\PersonRepository;
use App\Service\EditUpdate\EditUpdate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraveController extends AbstractController
{
    #[Route('/grave/{grave<\d+>}', name: 'app_grave', priority: 10)]
    public function show_grave(Request $request, Grave $grave, PersonRepository $personRepository, EditUpdate $editUpdate): Response
    {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // entity
        $person = new Person();

        // form
        $form_add_person = $this->createForm(NewPersonType::class, $person);
        $form_add_person->handleRequest($request);

        // query
        $not_assigned_people = $personRepository->findBy(
            ['grave' => null],
            ['id' => 'DESC']
        );

        // form handler
        if ($form_add_person->isSubmitted() && $form_add_person->isValid()) {
            $person = $form_add_person->getData();

            // save data
            $person->setGrave($grave);
            $editUpdate->updateBoth($grave, $person, $this->getUser(), 'person');

            // flash message
            $this->addFlash('success', 'Osoba dodana do pochówku!');

            // redirection
            $this->redirectToRoute('app_person', [
                'person' => $person->getId()
            ]);
        }

        return $this->render('grave/index.html.twig', [
            'grave' => $grave,
            'person' => $person,
            'people' => $not_assigned_people,
            'form_add_person' => $form_add_person
        ]);
    }

    #[Route('/grave/add', name: 'app_add_grave')]
    public function add_grave(Request $request, EditUpdate $editUpdate): Response
    {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // entity
        $grave = new Grave();

        // form
        $form = $this->createForm(NewGraveType::class, $grave);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $grave = $form->getData();

            // save data
            $editUpdate->updateOne($grave, $this->getUser(), true);

            // flash message
            $this->addFlash('success', 'Miejsce pochówku zostało dodane!');

            // redirection
            return $this->redirectToRoute('app_grave', [
                'grave' => $grave->getId()
            ]);
        }

        return $this->render('grave/add.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/grave/api/update/{grave<\d+>}', name: 'app_grave_api_update')]
    public function api_update_grave(Request $request, Grave $grave, PersonRepository $personRepository, EditUpdate $editUpdate): Response
    {
        // security
        $this->denyAccessUnlessGranted('ROLE_MANAGER');

        // form handler
        if ($request->isMethod('put')) {
            $external_request = json_decode($request->getContent(), true);
            if (count($external_request['assignToGrave'])) {
                foreach ($external_request['assignToGrave'] as $element) {
                    $person = $personRepository->find($element);

                    // save data
                    $grave->addPerson($person);
                    $editUpdate->updateBoth($grave, $person, $this->getUser(), false);
                }

                // flash message
                $this->addFlash('success', 'Przypisanie zakończone powodzeniem!');

                // return
                $data = true;

            } else {
                // flash message
                $this->addFlash('failed', 'Brak wybranych osób do przypisania!');

                // return
                $data = false;
            }

        } else {
            // return
            $data = false;
        }
        return new JsonResponse($data);
    }
}
