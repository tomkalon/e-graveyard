<?php

namespace App\Service\EditUpdate;

use App\Entity\Grave;
use App\Entity\Person;
use App\Entity\User;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use DateTime;

class EditUpdate
{
    public function __construct(
        private readonly PersonRepository $personRepository,
        private readonly GraveRepository $graveRepository
    ) {
    }

    public function updateOne(object $entity, object $user, bool $new): void
    {
        $entity->setEditedBy($user);
        $new === true ? $entity->setCreated(new DateTime()) : $entity->setEdited(new DateTime());
        if ($entity instanceof Person) {
            $this->personRepository->save($entity, true);
        } elseif ($entity instanceof Grave) {
            $this->graveRepository->save($entity, true);
        }
    }

    public function updateBoth(Grave $grave, Person $person, User $user, string $new): void
    {
        $grave->setEditedBy($user);
        $person->setEditedBy($user);
        if (!$new) {
            $grave->setEdited(new DateTime());
            $person->setEdited(new DateTime());
        } elseif ($new === 'grave') {
            $grave->setCreated(new DateTime());
            $person->setEdited(new DateTime());
        } elseif ($new === 'person') {
            $person->setCreated(new DateTime());
            $grave->setEdited(new DateTime());
        } elseif ($new === 'both') {
            $grave->setCreated(new DateTime());
            $person->setCreated(new DateTime());
        }
        $this->personRepository->save($person, true);
        $this->graveRepository->save($grave, true);
    }
}
