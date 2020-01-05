<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * @param int $ip
     * @return Ad[]
     */
    public function findByIp(int $ip)
    {
        //Traukia reklamas pagal mano paduota ip
        //Patikrina ar patenka i nustatytu ip rezius ir rikiuoja didejancia tvarka pagal id
        return $this->createQueryBuilder('a')
            ->where('a.ipFromNumber <= :ip')
            ->andWhere('a.ipToNumber >= :ip')
            ->setParameter('ip', $ip)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
