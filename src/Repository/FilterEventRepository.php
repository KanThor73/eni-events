<?php

namespace App\Repository;

use App\Entity\FilterEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FilterEvent>
 *
 * @method FilterEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method FilterEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method FilterEvent[]    findAll()
 * @method FilterEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilterEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FilterEvent::class);
    }

//    /**
//     * @return FilterEvent[] Returns an array of FilterEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FilterEvent
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
