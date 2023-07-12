<?php

namespace App\Service\EditUpdate;

use App\Entity\Grave;
use App\Entity\Person;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use App\Service\Logger\LoggerDependingOnClass;
use DateTime;
use Symfony\Bundle\SecurityBundle\Security;

class EditUpdate
{
    private object $user;

    public function __construct(
        private readonly PersonRepository $personRepository,
        private readonly GraveRepository $graveRepository,
        private readonly LoggerDependingOnClass $logger,
        Security $security
    ) {
        $this->user = $security->getUser();
    }

    // update Grave or Person data
    public function updateOne(object $entity, bool $new): void
    {
        $username = $this->user->getUsername();

        if ($new === true) {
            $entity->setCreated(new DateTime());
            // logs
            $this->logger->log($entity, 'Created', $username);
        } else {
            $entity->setEdited(new DateTime());
            // logs
            $this->logger->log($entity, 'Edited', $username);
        }
        $entity->setEditedBy($this->user);
        $this->personRepository->saveInstance($entity, true);
        $this->graveRepository->saveInstance($entity, true);
    }

    // update Grave and Person data
    public function updateBoth(Grave $grave, Person $person, string $new): void
    {
        $username = $this->user->getUsername();
        $grave->setEditedBy($this->user);
        $person->setEditedBy($this->user);

        switch ($new) {
            case 'grave':
                $grave->setCreated(new DateTime());
                $this->logger->log($grave, 'Created', $username);
                $person->setEdited(new DateTime());
                $this->logger->log($person, 'Edited', $username);
                break;
            case 'person':
                $person->setCreated(new DateTime());
                $this->logger->log($person, 'Created', $username);
                $grave->setEdited(new DateTime());
                $this->logger->log($grave, 'Edited', $username);
                break;
            case 'both':
                $grave->setCreated(new DateTime());
                $this->logger->log($grave, 'Created', $username);
                $person->setCreated(new DateTime());
                $this->logger->log($person, 'Created', $username);
                break;
            default:
                $grave->setEdited(new DateTime());
                $this->logger->log($grave, 'Edited', $username);
                $person->setEdited(new DateTime());
                $this->logger->log($person, 'Edited', $username);
                break;
        }
        $this->personRepository->save($person, true);
        $this->graveRepository->save($grave, true);
    }
}
