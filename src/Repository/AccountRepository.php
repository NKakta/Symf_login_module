<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
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


}
