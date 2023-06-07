<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Entity\Person;
use App\Form\SearchGraveType;
use App\Form\SearchPersonType;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/search/person/{page<\d+>}', name: 'app_search', priority: 10)]
    public function search_person(Request $request, PersonRepository $personRepository, int $page = 0): Response
    {
        // session
        $session = $request->getSession();

        // entity
        $person = new Person();

        // data
        $search_result = null;

        // query limit
        $limit = 15;

        // form
        $form = $this->createForm(SearchPersonType::class, $person);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $search_result = $personRepository->findPeople($person);
            if (!$search_result) {
                // flash message
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');
            }
        }

        return $this->render('main/search/search_person.html.twig', [
            'form' => $form,
            'search_result' => $search_result,
            'limit' => $limit,
            'page' => $page,
            'source' => 'person',
            'last_uri' => $request->headers->get('referer'),
        ]);
    }
    #[Route('/search/grave/{page<\d+>}', name: 'app_search_grave', priority: 10)]
    public function search_grave(Request $request, GraveRepository $graveRepository, int $page = 0): Response
    {
        // session
        $session = $request->getSession();

        // entity
        $grave = new Grave();

        // data
        $search_result = null;
        $select_name = null;

        // query limit
        $limit = 15;

        // form
        $form = $this->createForm(SearchGraveType::class, $grave);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $grave = $form->getData();
            $search_result = $graveRepository->findGraves($grave);
            if ($search_result) {
                $select_name = $grave->getGraveyard()->getName();
            } else {
                // flash message
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');
            }
        }

        return $this->render('main/search/search_grave.html.twig', [
            'form' => $form,
            'search_result' => $search_result,
            'limit' => $limit,
            'page' => $page,
            'source' => 'grave',
            'select_name' => $select_name,
            'last_uri' => $request->headers->get('referer'),
        ]);
    }


    #[Route('/test', name: 'app_tester')]
    public function test(): Response
    {
        return new JsonResponse(true);
    }

}
