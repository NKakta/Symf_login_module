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

    /**
     * @return Product[]
     */
    public function getAllOrdered(): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select('p')
            ->orderBy('p.price', 'DESC')
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $region
     * @param string $category
     * @return Product[]
     */
    public function findAllWithAccountCount(string $region, string $category): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb->addSelect('p')
            ->leftJoin('p.accounts', 'accounts')
            ->where('accounts.region = :region')
            ->andWhere('p.category = :category')
            ->addSelect('COUNT(accounts) AS accountCount')
            ->groupBy('p.id')
            ->setParameter('region', $region)
            ->setParameter('category', $category)
        ;
        return $qb->getQuery()->getResult();
    }

//    public function countNumberPrintedForCategory(Category $category)
//    {
//        $qb = $userRepository->createQueryBuilder('magazine')
//            ->addSelect("magazine.id,magazine.name,magazine.description")
//            ->leftJoin('magazine.wardrobe', 'wardrobe') // To show as well the magazines without wardrobes related
//            ->addSelect('COUNT(wardrobe.id) AS wardrobecount')
//            ->groupBy('magazine.id'); // To group the results per magazine
//    }
}
