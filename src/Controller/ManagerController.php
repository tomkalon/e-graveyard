<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Form\NewGraveType;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
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
        return $this->render('manager/index.html.twig', [
        ]);
    }

    #[Route('/manager/grave/show/{grave<\d+>}', name: 'app_manager_show_grave', priority: 10)]
    public function show_grave(Request $request, Grave $grave, GraveRepository $graveRepository): Response
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
                $grave->setCreated(new \DateTime());
            } else {
                $grave->setEdited(new \DateTime());
            }
            $graveRepository->save($grave, true);

            // flash message
            $this->addFlash('added', 'Miejsce pochówku zostało dodane!');

            // redirection
            return $this->redirectToRoute('app_manager_show_grave', [
                'grave' => $grave->getId()
            ]);
        }

        return $this->render('manager/add_grave.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/manager/person/api/get/{type}', name: 'app_manager_get_person')]
    public function api_get_people(Request $request, string $type, PersonRepository $personRepository): Response
    {
        if ($request->isMethod('get')) {
            switch ($type) {
                case 'not_assigned':
                    $data = 'ttteeessstt';
                    break;
                default:
                    $data = true;
                    break;
            }
        } else {
            $data = false;
        }
        return new JsonResponse($data);
    }
}
