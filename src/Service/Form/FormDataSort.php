<?php

namespace App\Service\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FormDataSort
{
    public function getLimit(Session $session, Request $request): int
    {
        // parameters
        $limit = 10;

        if ($request->query->has('form')) {
            $session->set('limit', $request->query->all()['form']['limit']);
        }

        if ($session->get('limit')) {
            $limit = $session->get('limit');
        }

        return $limit;
    }

    public function getGraveSort(Session $session, Request $request): string
    {
        // parameters
        $sort = 'graveyard;asc';

        if ($request->query->has('form')) {
            $session->set('grave_sort', $request->query->all()['form']['sort']);
        }

        if ($session->get('grave_sort')) {
            $sort = $session->get('grave_sort');
        }
        return $sort;
    }

    public function getPersonSort(Session $session, Request $request): string
    {
        // parameters
        $sort = 'name;asc';

        if ($request->query->has('form')) {
            $session->set('person_sort', $request->query->all()['form']['sort']);
        }

        if ($session->get('person_sort')) {
            $sort = $session->get('person_sort');
        }
        return $sort;
    }

    public function getGraveFormSort(FormBuilderInterface $builder, int $limit, string $sort): object
    {
        // form
        return $builder
            ->add('sort', ChoiceType::class, [
                'choices' => [
                    'graveyard_asc' => 'graveyard;asc',
                    'graveyard_desc' => 'graveyard;desc',
                    'sector_asc' => 'sector;asc',
                    'sector_desc' => 'sector;desc',
                    'row_asc' => 'row;asc',
                    'row_desc' => 'row;desc',
                    'number_asc' => 'number;asc',
                    'number_desc' => 'number;desc',
                ],
                'required' => false,
                'data' => $sort
            ])
            ->add('limit', ChoiceType::class, [
                'choices' => [
                    '10' => 10,
                    '25' => 25,
                    '50' => 50,
                ],
                'required' => false,
                'data' => $limit
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }

    public function getPersonFormSort(FormBuilderInterface $builder, int $limit, string $sort): object
    {
        // form
        return $builder
            ->add('sort', ChoiceType::class, [
                'choices' => [
                    'name_asc' => 'name;asc',
                    'name_desc' => 'name;desc',
                    'surname_asc' => 'surname;asc',
                    'surname_desc' => 'surname;desc',
                    'born_asc' => 'born;asc',
                    'born_desc' => 'born;desc',
                    'death_asc' => 'death;asc',
                    'death_desc' => 'death;desc',
                ],
                'required' => false,
                'data' => $sort
            ])
            ->add('limit', ChoiceType::class, [
                'choices' => [
                    '10' => 10,
                    '25' => 25,
                    '50' => 50,
                ],
                'required' => false,
                'data' => $limit
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
    }
}
