<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Order;
use App\Entity\Product;
use App\Model\AccountFilterModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AccountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param string $id
     * @return void
     */
    public function findById(string $id)
    {
        $this->findBy(['id' => $id]);
    }

    /**
     * @param AccountFilterModel $filter
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery(AccountFilterModel $filter)
    {
        $qb = $this->createQueryBuilder('acc');

        if ($filter->getUsername()) {
            $qb
                ->where('acc.username = :username')
                ->setParameter('username', $filter->getUsername())
            ;
        }
        return $qb->getQuery();
    }

    /**
     * @param Order $order
     * @param int $amount
     * @return Account[]
     */
    public function getAvailableAccountsByOrder(Order $order, int $amount)
    {
        $qb = $this->createQueryBuilder('acc');

        $qb
            ->where('acc.product = :product')
            ->andWhere('acc.sold = 0')
            ->setParameter('product', $order->getProduct())
            ->setMaxResults($amount)
        ;
        return $qb->getQuery()->getResult();
    }


}
