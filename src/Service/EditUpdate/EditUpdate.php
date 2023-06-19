<?php

namespace App\Service\EditUpdate;

use App\Entity\Grave;
use App\Entity\Person;
use App\Entity\User;
use App\Repository\GraveRepository;
use App\Repository\PersonRepository;
use DateTime;
use Psr\Log\LoggerInterface;

class EditUpdate
{
    public function __construct(
        private readonly PersonRepository $personRepository,
        private readonly GraveRepository $graveRepository,
        private readonly LoggerInterface $changesLogger,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function updateOne(object $entity, object $user, bool $new): void
    {
        if ($new === true) {
            // logs
            $this->changesLogger->info($this->translator->trans('Utworzono -> '), [
                $user->getUsername(),
                $_SERVER['REMOTE_ADDR']
            ]);
            // set creation time
            $entity->setCreated(new DateTime());
        } else {
            // logs
            $this->changesLogger->info($this->translator->trans('Utworzono -> '), [
                $user->getUsername(),
                $_SERVER['REMOTE_ADDR']
            ]);
            // set edit time
            $entity->setEdited(new DateTime());
        }
        $entity->setEditedBy($user);
        if ($entity instanceof Person) {
            $this->personRepository->save($entity, true);
        } elseif ($entity instanceof Grave) {
            $this->graveRepository->save($entity, true);
        }
    }

    public function updateBoth(Grave $grave, Person $person, User $user, string $new): void
    {
        $this->changesLogger->info('Joł', [
            $user->getUsername(),
            $_SERVER['REMOTE_ADDR']
        ]);
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
