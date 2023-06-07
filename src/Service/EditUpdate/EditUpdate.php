<?php

namespace App\Service\EditUpdate;

use App\Entity\Grave;
use App\Entity\Person;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class EditUpdate
{
     private object $grave;
     private object $person;

    public function __construct(EntityManagerInterface $entityManager)
    {
         $this->grave = $entityManager->getRepository(Grave::class);
         $this->person = $entityManager->getRepository(Person::class);
    }

    public function updateOne(object $entity, object $user, bool $new): void
    {
        $username = $user->getUsername();
        $entity->setEditedBy($username);
        $new === true ? $entity->setCreated(new DateTime()) : $entity->setEdited(new DateTime());

        $class_name = get_class($entity);
        switch ($class_name) {
            case "App\Entity\Person":
                $this->person->save($entity, true);
                break;
            case "App\Entity\Grave":
                $this->grave->save($entity, true);
                break;
        }
    }

    public function updateBoth(object $grave, object $person, object $user, string $new): void
    {
        $username = $user->getUsername();
        $grave->setEditedBy($username);
        $person->setEditedBy($username);
        if (!$new) {
            $grave->setEdited(new DateTime());
            $person->setEdited(new DateTime());
        } else if ($new === 'grave') {
            $grave->setCreated(new DateTime());
            $person->setEdited(new DateTime());
        } else if ($new === 'person') {
            $person->setCreated(new DateTime());
            $grave->setEdited(new DateTime());
        } else if ($new === 'both') {
            $grave->setCreated(new DateTime());
            $person->setCreated(new DateTime());
        }
        $this->person->save($person, true);
        $this->grave->save($grave, true);
    }
}