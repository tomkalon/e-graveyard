<?php

namespace App\Service\Person;

use App\Repository\PersonRepository;

class PersonManager
{
    public function __construct(private readonly PersonRepository $personRepository)
    {
    }

    public function getNotAssignedPeople(): array
    {
        $data = array();
        $query = $this->personRepository->findBy(
            ['grave' => null],
            ['id' => 'DESC']
        );

        foreach ($query as $key => $item) {
            $data[$key]['id'] = $item->getId();
            $data[$key]['surname'] = $item->getSurname();
            $data[$key]['name'] = $item->getName();
            $data[$key]['born'] = date_format($item->getBorn(), 'Y-m-d');
            $data[$key]['death'] = date_format($item->getDeath(), 'Y-m-d');
        }

        return $data;
    }
}
