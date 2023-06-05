<?php

namespace App\Service\Person\EditUpdate;

use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class EditUpdate
{
    // private object $grave;
    // private object $person;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // $this->grave = $entityManager->getRepository(GraveRepository::class);
        // $this->person = $entityManager->getRepository(PersonRepository::class);
    }

    public function updateOne(object $entity, string $username, bool $new): void
    {
        $entity->setEditedBy($username);
        $new === true ? $entity->setCreated(new DateTime()) : $entity->setEdited(new DateTime());
    }

    public function updateBoth(object $grave, object $person, string $username, string $new): void
    {

    }
}