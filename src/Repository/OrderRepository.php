<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Order;
use App\Model\OrderFilterModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param OrderFilterModel $filter
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery(OrderFilterModel $filter)
    {
        $qb = $this->createQueryBuilder('p');

        if ($filter->getName()) {
            $qb
                ->where('p.name LIKE :name')
                ->orWhere('p.payerEmail LIKE :name')
                ->orWhere('p.orderId LIKE :name')
                ->setParameter('name', '%'.$filter->getName().'%')
            ;
        }
        return $qb->getQuery();
    }
}
