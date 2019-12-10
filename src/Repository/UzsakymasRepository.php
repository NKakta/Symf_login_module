<?php

namespace App\Repository;

use App\Entity\Uzsakymas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Uzsakymas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Uzsakymas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Uzsakymas[]    findAll()
 * @method Uzsakymas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UzsakymasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Uzsakymas::class);
    }

    // /**
    //  * @return Uzsakymas[] Returns an array of Uzsakymas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Uzsakymas
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
