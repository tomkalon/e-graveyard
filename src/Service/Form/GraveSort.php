<?php

namespace App\Service\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GraveSort
{
    public function getLimit(Session $session): int
    {
        // parameters
        $limit = 10;

        if (isset($_GET['form']['limit'])) {
            $session->set('limit', $_GET['form']['limit']);
        }

        if ($session->get('limit')) {
            $limit = $session->get('limit');
        }

        return $limit;
    }

    public function getGraveSort(Session $session): string
    {
        // parameters
        $sort = 'graveyard;asc';

        if (isset($_GET['form']['sort'])) {
            $session->set('grave_sort', $_GET['form']['sort']);
        }

        if ($session->get('grave_sort')) {
            $sort = $session->get('grave_sort');
        }
        return $sort;
    }

    public function getGraveSortForm(FormBuilderInterface $builder, int $limit, string $sort): object
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

}