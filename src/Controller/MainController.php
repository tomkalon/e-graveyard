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

    #[Route('/search', name: 'app_search', priority: 10)]
    public function searchPage(Request $request, PersonRepository $personRepository): Response
    {
        // session
        $session = $request->getSession();

        // form
        $person = new Person();
        $form = $this->createForm(SearchGraveType::class, $person);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $search_result = $personRepository->findPeople($person);
            if ($search_result) {
                $session->set('search_result', $search_result);
                return $this->redirectToRoute('app_search_result');
            } else {
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');
                return $this->redirectToRoute('app_search');
            }
        }

        return $this->render('main/search.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/search/result/{page<\d+>}', name: 'app_search_result', priority: 5)]
    public function searchResultPage(Request $request, PersonRepository $personRepository, int $page = 0): Response
    {
        // session
        $session = $request->getSession();
        $session->set('last_uri', $request->getUri());

        // matching search data
        $search_result = $session->get('search_result');

        // query limit
        $limit = 15;

        return $this->render('main/search/result.html.twig', [
            'search_result' => $search_result,
            'page' => $page,
            'limit' => $limit
        ]);
    }
}
