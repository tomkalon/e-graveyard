<?php

namespace App\Controller;

use App\Entity\Grave;
use App\Entity\Person;
use App\Form\SearchGraveType;
use App\Form\SearchPersonType;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use App\Service\Form\FormDataSort;
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
    public function search_person(Request $request, PersonRepository $personRepository, FormDataSort $dataSort): Response
    {
        // session
        $session = $request->getSession();
        $sort = $dataSort->getPersonSort($session);

        // entity
        $person = new Person();

        // form
        $form = $this->createForm(SearchPersonType::class, $person);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $search_result = $personRepository->findPeople($person, $sort);
            if (!$search_result) {
                // flash message
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');
            } else {
                $session->set('search_result_person', $search_result);
                $session->set('search_query_person', $person);
                return $this->redirectToRoute('app_search_result', [
                    'type' => 'person',
                ]);
            }
        }

        return $this->render('main/search/search_person.html.twig', [
            'form' => $form,
            'last_uri' => $request->headers->get('referer'),
        ]);
    }
    #[Route('/search/grave', name: 'app_search_grave', priority: 10)]
    public function search_grave(Request $request, GraveRepository $graveRepository, FormDataSort $dataSort): Response
    {
        // session
        $session = $request->getSession();
        $sort = $dataSort->getGraveSort($session);

        // entity
        $grave = new Grave();

        // form
        $form = $this->createForm(SearchGraveType::class, $grave);
        $form->handleRequest($request);

        // form handler
        if ($form->isSubmitted() && $form->isValid()) {
            $grave = $form->getData();
            $search_result = $graveRepository->findGraves($grave, $sort);
            if (!$search_result) {
                // flash message
                $this->addFlash('failed', 'Brak wyników spełniających kryteria wyszukiwania. Spróbuj ponownie!');
            } else {
                $session->set('search_result_grave', $search_result);
                $session->set('search_query_grave', $grave);
                return $this->redirectToRoute('app_search_result', [
                    'type' => 'grave',
                ]);
            }
        }

        return $this->render('main/search/search_grave.html.twig', [
            'form' => $form,
            'last_uri' => $request->headers->get('referer'),
        ]);
    }

    #[Route('/search/result/{type}/{page<\d+>}', name:'app_search_result')]
    public function result(Request $request, FormDataSort $dataSort,
                           PersonRepository $personRepository, GraveRepository $graveRepository, string $type, int $page = 0): Response
    {
        // session
        $session = $request->getSession();
        $limit = $dataSort->getLimit($session);

        $search_result = false;
        $form_sort = false;

        if ($type === 'person') {
            $sort = $dataSort->getPersonSort($session);
            if (isset($_GET['form']['sort'])) {
                $person = $session->get('search_query_person');
                $search_result = $personRepository->findPeople($person, $sort);
            } else {
                $search_result = $session->get('search_result_person');
            }
            $form_sort = $dataSort->getPersonFormSort($this->createFormBuilder(), $limit, $sort);
        } elseif ($type === 'grave') {
            $sort = $dataSort->getGraveSort($session);
            if (isset($_GET['form']['sort'])) {
                $grave = $session->get('search_query_grave');
                $search_result = $graveRepository->findGraves($grave, $sort);
            } else {
                $search_result = $session->get('search_result_grave');
            }
            $form_sort = $dataSort->getGraveFormSort($this->createFormBuilder(), $limit, $sort);
        }

        if (!$search_result or  !$form_sort) {
            // messages
            $this->addFlash('failed', 'Sesja wygasła!');

            // redirection
            return $this->redirectToRoute('app_search');
        }

        return $this->render('main/search/search_result.html.twig', [
            'form' => $form_sort,
            'search_result' => $search_result,
            'source' => $type,
            'limit' => $limit,
            'page' => $page,
            'last_uri' => $request->headers->get('referer'),
        ]);
    }

    #[Route('/test', name: 'app_tester')]
    public function test(): Response
    {
        return new JsonResponse(true);
    }

}
