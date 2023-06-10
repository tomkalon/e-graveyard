<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 *
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function save(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Person $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPeople(Person $person, string $sort): array
    {
        $arr = explode(';', $sort);

        $name = $person->getName();
        $surname = $person->getSurname();
        $born = $person->getBorn();
        $death = $person->getDeath();

        $qb = $this->createQueryBuilder('p')
            ->where('p.name = :name')
            ->setParameter('name', $name);

        if ($surname) {
            $qb->andWhere('p.surname = :surname')
                ->setParameter('surname', $surname);
        }
        if ($born) {
            $qb->andWhere('p.born = :born')
                ->setParameter('born', $born);
        }
        if ($death) {
            $qb->andWhere('p.death = :death')
                ->setParameter('death', $death);
        }

        if (count($arr) > 1) {
            $qb->orderBy("p.$arr[0]", $arr[1]);
        }

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findUnassigned(string $sort): array
    {
        $arr = explode(';', $sort);

        $qb = $this->createQueryBuilder('p')
            ->where('p.grave is NULL');

        if (count($arr) > 1) {
            $qb->orderBy("p.$arr[0]", $arr[1]);
        }

        $query = $qb->getQuery();
        return $query->execute();
    }


//    /**
//     * @return Person[] Returns an array of Person objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Person
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
