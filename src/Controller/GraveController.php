<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraveController extends AbstractController
{
    #[Route('/grave', name: 'app_grave')]
    public function index(): Response
    {
        return $this->render('grave/index.html.twig', [
            'controller_name' => 'GraveController',
        ]);
    }

    #[Route('/grave/api/update/{grave}', name: 'app_grave_api_update_')]
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
                $this->addFlash('failed', 'Brak wybranych osób do przypisania!');
                $data = false;
            }

        } else {
            $data = false;
        }
        return new JsonResponse($data);
    }
}
