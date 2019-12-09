<?php

namespace App\Repository;

use App\Entity\Choises;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Choises|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choises|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choises[]    findAll()
 * @method Choises[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoisesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Choises::class);
    }

    // /**
    //  * @return Choises[] Returns an array of Choises objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Choises
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
