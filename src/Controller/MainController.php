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

    #[Route('/search/person', name: 'app_search', priority: 10)]
    public function search_person(Request $request, PersonRepository $personRepository): Response
    {
        // session
        $session = $request->getSession();
        $session->set('last_uri', $request->getUri());

        // entity
        $person = new Person();

        // form
        $form = $this->createForm(SearchPersonType::class, $person);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $search_result = $personRepository->findPeople($person);
            if ($search_result) {
                $session->set('search_result', $search_result);
                $session->set('search_result_source', 'person');

                // redirection
                return $this->redirectToRoute('app_search_result');
            } else {

                // flash message
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');

                // redirection
                return $this->redirectToRoute('app_search');
            }
        }

        return $this->render('main/search.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/search/grave', name: 'app_search_grave', priority: 10)]
    public function search_grave(Request $request, GraveRepository $graveRepository): Response
    {
        // session
        $session = $request->getSession();
        $session->set('last_uri', $request->getUri());

        // entity
        $grave = new Grave();

        // form
        $form = $this->createForm(SearchGraveType::class, $grave);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $grave = $form->getData();
            $search_result = $graveRepository->findGraves($grave);
            if ($search_result) {
                $session->set('search_result', $search_result);
                $session->set('search_result_source', 'grave');
                $session->set('select_name', $grave->getGraveyard()->getName());

                // redirection
                return $this->redirectToRoute('app_search_result');
            } else {

                // flash message
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');

                // redirection
                return $this->redirectToRoute('app_search');
            }
        }

        return $this->render('main/search_grave.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/search/result/{page<\d+>}', name: 'app_search_result', priority: 5)]
    public function searchResultPage(Request $request, int $page = 0): Response
    {
        // session
        $session = $request->getSession();
        $session->set('last_uri', $request->getUri());
        $source = $session->get('search_result_source');
        $search_result = $session->get('search_result');
        $select_name = match ($source) {
            'grave' => $session->get('select_name'),
            default => false
        };

        // matching search data
        if (!$search_result) {
            $this->addFlash('failed', 'Sesja wygasła!');
            return match ($source) {
                'person' => $this->redirectToRoute('app_search'),
                'grave' => $this->redirectToRoute('app_search_grave'),
                default => $this->redirectToRoute('app_main'),
            };
        }

        // query limit
        $limit = 15;

        return $this->render('main/search/result.html.twig', [
            'search_result' => $search_result,
            'page' => $page,
            'limit' => $limit,
            'source' => $source,
            'select_name' => $select_name
        ]);
    }


    #[Route('/test', name: 'app_tester')]
    public function test(): Response
    {
        return new JsonResponse(true);
    }

}
