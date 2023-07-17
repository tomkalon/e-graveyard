<?php

namespace App\Service\Logger;

use App\Entity\Grave;
use App\Entity\Person;
use Psr\Log\LoggerInterface;

class LoggerDependingOnClass
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function log(object $entity, string $action, string $author): void
    {
        $user = $author . ':' . $_SERVER['REMOTE_ADDR'];
        $data = '';

        if ($entity instanceof Person) {
            $data = 'USER: ' . $entity->getSurname() . $entity->getName() . ' - ID: ' . $entity->getId();
        }
        if ($entity instanceof Grave) {
            $data = 'GRAVE: ' . $entity->getGraveyard()->getName() .
                ' S:' . $entity->getSector() .
                ' R:' . $entity->getRow() .
                ' N:' . $entity->getNumber();
        }
        $this->logger->info($action . ':: ' . $data . ' -> ' . $user);
    }
}
