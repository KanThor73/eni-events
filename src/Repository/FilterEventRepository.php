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

    public function findDynamic($userId, $campus, $params): array
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.campus = :val')
            ->setParameter('val', $campus->getId());

        if (!empty($params->getEventName())) {
            $searchWord = $params->get('eventName');
            $query
                ->andWhere($query->expr()->like('e.name', $query->expr()->literal('%' . ':chaine' . '%')))
                ->setParameter('chaine', $params->get('searchWord'));
        }
        if (!empty($params->getBeginDate())) {
            $query
                ->andWhere($query->expr()->gte('e.beginDate', ':dateDebut'))
                ->setParameter('dateDebut', $params->get('beginDate'));
        }
        if (!empty($params->getEndDate())) {
            $query
                ->andWhere($query->expr()->lte('e.beginDate', ':dateFin'))
                ->setParameter('dateFin', $params->get('endDate'));
        }
        if ($params->isOrganizer()) {
            $query
                ->andWhere('e.organizer = :orgaId')
                ->setParameter('orgaId', $userId);
        }
        if ($params->isMember()) {

        }
        if ($params->isNotMember()) {

        }
        if ($params->isPassed()) {
            $query
                ->andWhere($query->expr()->gt('e.beginDate', 'CURRENT_DATE()'));
        }
        return $query->getQuery()->getResult();
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
