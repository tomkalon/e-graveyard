<?php

namespace App\Service\Person;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;

class PersonManager
{
    private object $person;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->person = $entityManager->getRepository(Person::class);
    }

    public function getNotAssignedPeople (): array
    {
        $data = array();
        $query = $this->person->findBy(
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