<?php

namespace App\DataFixtures;

use App\Entity\Grave;
use App\Entity\Graveyard;
use App\Entity\Person;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private array $names = array(
        ['Jan','Kowalski', 1939, 3, 28, 2000, 1, 29],
        ['Jan', 'Nowak', 1956, 4, 11, 1982, 11, 3],
        ['Janina', 'Kowalska', 1999, 11, 11, 2001, 1, 12],
        ['Janina', 'Nowak', 1900, 2, 21, 1977, 3, 1],
        ['Władysław', 'Reymont', 1989, 6, 6, 2020, 1, 22],
        ['Adam', 'Mickiewicz', 1922, 11, 11, 2016, 12, 25],
        ['Jacek', 'Soplica', 1943, 10, 11, 2017, 9, 19],
        ['Maria', 'Konopnicka', 1943, 5, 5, 2002, 8, 13],
        ['Wisława', 'Szymborska', 1899, 12, 15, 1988, 7, 8],
        ['Andrzej', 'Lepper', 1933, 5, 3, 2020, 4, 18],
        ['Alicja', 'Wójcik', 1923, 8, 12, 2019, 8, 13],
        ['Ewa', 'Lewandowska', 1935, 11, 14, 2015, 10, 7],
        ['Janusz','Kowalski', 1941, 2, 21, 1999, 3, 22],
        ['Woland','Kowalski', 1943, 1, 1, 1998, 1, 28]
    );

    private array $graves = array(
        [1, 3, 3, 49.992813, 20.513154, 2014, 11, 11],
        [1, 3, 4, 49.992816, 20.513175, false, false, false],
        [1, 3, 5, 49.992822, 20.513202, false, false, false],
        [1, 3, 6, 49.992832, 20.513233, false, false, false],
        [1, 3, 7, 49.992840, 20.513261, false, false, false],
        [1, 3, 8, 49.992850, 20.513286, 2009, 3, 6]
    );

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $this->makeUsers($manager);
        $graveyard = $this->makeGraveyard($manager);
        $people = $this->makePeople($manager, $this->names);
        $this->makeGraves($manager, $graveyard, $this->graves, $people);

        $manager->flush();
    }

    private function makeUsers($manager): void
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUsername('tester');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);
    }

    private function makeGraveyard($manager): object
    {
        $graveyard = new Graveyard();
        $graveyard->setName('Nowy cmentarz');
        $graveyard->setDescription('Nowy cmentarz przy ulicy Wiśniowej');
        $manager->persist($graveyard);
        return $graveyard;
    }

    private function makePeople($manager, $names): array
    {
        $arr = array();

        foreach ($names as $key => $item) {
            $arr[$key] = new Person();
            $arr[$key]->setSurname('Jan');
            $arr[$key]->setName('Kowalski');
            $arr[$key]->setSurname($item[0]);
            $arr[$key]->setName($item[1]);

            $date = new DateTime();
            $date->format('Y-m-d');
            $date->setTimezone(new DateTimeZone('Europe/Warsaw'));
            $arr[$key]->setCreated($date);
            $date = new DateTime();
            $date->format('Y-m-d');
            $date->setTimezone(new DateTimeZone('Europe/Warsaw'));
            $date->setDate($item[2], $item[3], $item[4]);
            $arr[$key]->setBorn($date);
            $date = new DateTime();
            $date->format('Y-m-d');
            $date->setTimezone(new DateTimeZone('Europe/Warsaw'));
            $date->setDate($item[5], $item[6], $item[7]);
            $arr[$key]->setDeath($date);
            $manager->persist($arr[$key]);
        }
        return $arr;
    }

    private function makeGraves($manager, $graveyard, $graves, $people): void
    {
        $arr = array();

        foreach ($graves as $key => $item) {
            $arr[$key] = new Grave();
            $arr[$key]->setGraveyard($graveyard);
            $arr[$key]->setSector($item[0]);
            $arr[$key]->setRow($item[1]);
            $arr[$key]->setNumber($item[2]);
            $arr[$key]->setPositionY($item[3]);
            $arr[$key]->setPositionX($item[4]);

            $date = new DateTime();
            $date->format('Y-m-d');
            $arr[$key]->setCreated($date);

            if ($item[5]) {
                $date->format('Y-m-d');
                $date->setDate($item[5], $item[6], $item[7]);
                $arr[$key]->setPaid($date);
            }

            switch ($key) {
                case 0:
                    $arr[$key]->addPerson($people[0]);
                    $arr[$key]->addPerson($people[1]);
                    $arr[$key]->addPerson($people[2]);
                    $arr[$key]->addPerson($people[3]);
                    break;
                case 1:
                    $arr[$key]->addPerson($people[4]);
                    $arr[$key]->addPerson($people[5]);
                    $arr[$key]->addPerson($people[6]);
                    break;
                case 2:
                    $arr[$key]->addPerson($people[7]);
                    $arr[$key]->addPerson($people[8]);
                    break;
                case 3:
                    $arr[$key]->addPerson($people[9]);
                    break;
                case 4:
                    $arr[$key]->addPerson($people[10]);
                    break;
                case 5:
                    $arr[$key]->addPerson($people[11]);
                    $arr[$key]->addPerson($people[12]);
                    $arr[$key]->addPerson($people[13]);
                    break;
            }

            $manager->persist($arr[$key]);
        }
    }
}
