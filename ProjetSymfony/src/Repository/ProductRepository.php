<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

       /**
        * @return Product[] Returns an array of Product objects
        */
       public function findAllOrderedByName(): array
       {
           return $this->createQueryBuilder('p')
               ->orderBy('p.nom', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }
}
