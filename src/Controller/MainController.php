<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\SearchGraveType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/search/{page}', name: 'app_search', priority: 10)]
    public function searchPage(Request $request, PersonRepository $personRepository, int $page = 0): Response
    {
        // matching search data
        $search_result = array();

        $person = new Person();
        $form = $this->createForm(SearchGraveType::class, $person, [
            'action' => $this->generateUrl('app_search', [
                'page' => $page
            ]),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $search_result = $personRepository->findByObjectData($person);
            $search_result
                ? $this->addFlash('success', 'Wyniki wyszukiwania:')
                : $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania.');
        }

        return $this->render('main/search.html.twig', [
            'form' => $form,
            'search_result' => $search_result
        ]);
    }
}
