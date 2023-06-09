<?php

namespace App\Repository;

use App\Entity\Grave;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grave>
 *
 * @method Grave|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grave|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grave[]    findAll()
 * @method Grave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GraveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grave::class);
    }

    public function save(Grave $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveInstance(object $entity, bool $flush = false): bool
    {
        if ($entity instanceof Grave) {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
            return true;
        } else {
            return false;
        }
    }

    public function remove(Grave $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findGraves(Grave $grave, string $sort): array
    {
        $arr = explode(';', $sort);

        $graveyard = $grave->getGraveyard();
        $sector = $grave->getSector();
        $row = $grave->getRow();
        $number = $grave->getNumber();

        $qb = $this->createQueryBuilder('g')
            ->where('g.graveyard = :graveyard')
            ->setParameter('graveyard', $graveyard);

        if ($sector) {
            $qb->andWhere('g.sector = :sector')
                ->setParameter('sector', $sector);
        }
        if ($row) {
            $qb->andWhere('g.row = :row')
                ->setParameter('row', $row);
        }
        if ($number) {
            $qb->andWhere('g.number = :number')
                ->setParameter('number', $number);
        }

        if (count($arr) > 1) {
            $qb->addOrderBy("g.$arr[0]", $arr[1]);
        }

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findUnassigned(string $sort): array
    {
        $arr = explode(';', $sort);

        $qb = $this->createQueryBuilder('g')
            ->leftJoin('g.people', 'p')
            ->where('p.id is NULL');
        if (count($arr) > 1) {
            $qb->addOrderBy("g.$arr[0]", $arr[1]);
        }

        $query = $qb->getQuery();
        return $query->execute();
    }

//    /**
//     * @return Grave[] Returns an array of Grave objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Grave
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
