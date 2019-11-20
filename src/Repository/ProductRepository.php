<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Category;
use App\Entity\Product;
use App\Model\ProductFilterModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string|null $id
     * @return Product[]
     */
    public function findById(?string $id)
    {
        return $this->findBy(['id' => $id]);
    }

    /**
     * @param Category|null $category
     * @return Product[]
     */
    public function findByCategory(?Category $category): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where('p.category = :category')
            ->setParameter('category', $category);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param ProductFilterModel $filter
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery(ProductFilterModel $filter)
    {
        $qb = $this->createQueryBuilder('p');

        if ($filter->getName()) {
            $qb
                ->where('p.name = :name')
                ->setParameter('name', $filter->getName())
            ;
        }
        return $qb->getQuery();
    }
}
