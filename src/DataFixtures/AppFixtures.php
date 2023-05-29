<?php

namespace App\DataFixtures;

use App\Entity\Grave;
use App\Entity\Graveyard;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private array $names = array(
        ['Jan','Kowalski'],
        ['Jan', 'Nowak'],
        ['Janina', 'Kowalska'],
        ['Janina', 'Nowak'],
        ['Władysław', 'Reymont'],
        ['Adam', 'Mickiewicz'],
        ['Maria', 'Konopnicka'],
        ['Jacek', 'Soplica'],
        ['Wisława', 'Szymborska'],
        ['Beata', 'Kempa'],
        ['Alicja', 'Wójcik'],
        ['Ewa', 'Lewandowska'],
    );
    public function __construct (private UserPasswordHasherInterface $hasher)
    {

    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->makeUsers($manager);
        $graveyard = $this->makeGraveyard($manager);
        $this->makeGraves($manager, $graveyard);

        $manager->flush();
    }

    private function makeUsers ($manager): void
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUsername('tester');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);
    }

    private function makeGraveyard ($manager): object
    {
        $graveyard = new Graveyard();
        $graveyard->setName('Nowy cmentarz');
        $graveyard->setDescription('Nowy cmentarz przy ulicy Wiśniowej');
        $manager->persist($graveyard);
        return $graveyard;
    }

    private function makePeople ($manager, $names): array
    {
        $arr = array();
        foreach ($names as $key => $item){
            $arr[$key] = new Person();
            $arr[$key]->setSurname($item[0]);
            $arr[$key]->setName($item[1]);

        }
        return array();
    }

    private function makeGraves ($manager, $graveyard): void
    {

    }
}
