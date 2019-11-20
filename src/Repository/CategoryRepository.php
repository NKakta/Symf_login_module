<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use App\Model\CategoryFilterModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param string|null $id
     * @return Category
     */
    public function findById(?string $id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param CategoryFilterModel $filter
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery(CategoryFilterModel $filter)
    {
        $qb = $this->createQueryBuilder('cat');

        if ($filter->getName()) {
            $qb
                ->where('cat.name = :name')
                ->setParameter('name', $filter->getName())
            ;
        }
        return $qb->getQuery();
    }
}
