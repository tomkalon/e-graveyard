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
                    'GRAVEYARD_ASC' => 'graveyard;asc',
                    'GRAVEYARD_DESC' => 'graveyard;desc',
                    'SECTOR_ASC' => 'sector;asc',
                    'SECTOR_DESC' => 'sector;desc',
                    'ROW_ASC' => 'row;asc',
                    'ROW_DESC' => 'row;desc',
                    'NUMBER_ASC' => 'number;asc',
                    'NUMBER_DESC' => 'number;desc',
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
            ->add('submit', SubmitType::class, [
                'label' => 'SUBMIT',
                'attr' => ['class' => 'btn-sort']
            ])
            ->getForm();
    }

    public function getPersonFormSort(FormBuilderInterface $builder, int $limit, string $sort): object
    {
        // form
        return $builder
            ->add('sort', ChoiceType::class, [
                'choices' => [
                    'NAME_ASC' => 'name;asc',
                    'NAME_DESC' => 'name;desc',
                    'SURNAME_ASC' => 'surname;asc',
                    'SURNAME_DESC' => 'surname;desc',
                    'BORN_ASC' => 'born;asc',
                    'BORN_DESC' => 'born;desc',
                    'DEATH_ASC' => 'death;asc',
                    'DEATH_DESC' => 'death;desc',
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
            ->add('submit', SubmitType::class, [
                'label' => 'SUBMIT',
                'attr' => ['class' => 'btn-sort']
            ])
            ->getForm();
    }
}
