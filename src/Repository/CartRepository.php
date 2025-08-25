<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findWithSweatAndSizeByCustomer(Customer $customer): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.SweatVariant', 'sv')
            ->addSelect('sv')
            ->innerJoin('sv.sweat', 's')
            ->addSelect('s')
            ->innerJoin('sv.size', 'sz')
            ->addSelect('sz')
            ->where('c.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
