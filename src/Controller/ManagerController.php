<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Entity\Person;
use App\Form\NewGraveType;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use App\Service\Person\PersonManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/manager/grave/show/{grave<\d+>}', name: 'app_manager_show_grave', priority: 10)]
    public function show_grave(Grave $grave): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        return $this->render('manager/show_grave.html.twig', [
            'grave' => $grave
        ]);
    }

    #[Route('/manager/grave/add', name: 'app_manager_add_grave')]
    public function add_grave(Request $request, GraveRepository $graveRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        $grave = new Grave();

        $form = $this->createForm(NewGraveType::class, $grave);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $grave = $form->getData();
            $user = $this->getUser();
            $grave->setEditedBy($user->getUsername());
            if ($grave->getCreated() === null) {
                $grave->setCreated(new DateTime());
            } else {
                $grave->setEdited(new DateTime());
            }
            $graveRepository->save($grave, true);

            // flash message
            $this->addFlash('success', 'Miejsce pochówku zostało dodane!');

            // redirection
            return $this->redirectToRoute('app_manager_show_grave', [
                'grave' => $grave->getId()
            ]);
        }

        return $this->render('manager/add_grave.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/manager/person/api/update/{person}', name: 'app_manager_update_person')]
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
            }

        } else {
            $data = false;
        }
        return new JsonResponse($data);
    }

    #[Route('/manager/person/api/get/{type}', name: 'app_manager_get_person')]
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

    #[Route('/manager/grave/api/update/{grave}', name: 'app_manager_update_grave')]
    public function api_update_grave(Request $request, Grave $grave, PersonRepository $personRepository, GraveRepository $graveRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        if ($request->isMethod('put')) {
            $external_request = json_decode($request->getContent(), true);
            if (count($external_request['assignToGrave'])) {
                foreach ($external_request['assignToGrave'] as $element) {
                    $person = $personRepository->find($element);
                    $grave->addPerson($person);
                    $graveRepository->save($grave, true);
                }
                $data = true;
                $this->addFlash('success', 'Przypisanie zakończone powodzeniem!');
            } else {
                $data = false;
            }

        } else {
            $data = false;
        }
        return new JsonResponse($data);
    }
}
