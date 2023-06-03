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
}
