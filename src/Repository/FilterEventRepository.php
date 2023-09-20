<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\FilterEvent;
use App\Entity\State;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * // * @extends ServiceEntityRepository<FilterEvent>
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
        parent::__construct($registry, Event::class);
    }

    public function findDynamic($user, $params, $state): array
    {

        $query = $this->createQueryBuilder('e');

        if (!empty($params->getCampus())) {
            $query
                ->andWhere('e.campus = :campusSelect')
                ->setParameter('campusSelect', $params->getCampus());
        }
        if (!empty($params->getEventName())) {
            $searchWord = '%' . $params->getEventName() . '%';
            $query
                ->andWhere($query->expr()->like('e.name', ':chaine'))
                ->setParameter('chaine', $searchWord);
        }
        if (!empty($params->getBeginDate())) {
            $query
                ->andWhere($query->expr()->gte('e.beginDate', ':dateDebut'))
                ->setParameter('dateDebut', $params->getBeginDate());
        }
        if (!empty($params->getEndDate())) {
            $query
                ->andWhere($query->expr()->lte('e.beginDate', ':dateFin'))
                ->setParameter('dateFin', $params->getEndDate());
        }
        if ($params->isOrganizer()) {
            $query
                ->andWhere('e.organizer = :orgaId')
                ->setParameter('orgaId', $user);
        }
        if ($params->isMember()) {
            $query
                ->join('e.members', 'm')
                ->andWhere($query->expr()->eq('m', ':member'))
                ->setParameter('member', $user);
        }
        if ($params->isNotMember()) {
            $query
                ->join('e.members', 'm')
                ->andWhere($query->expr()->neq('m', ':member'))
                ->setParameter('member', $user);
        }
        if ($params->isPassed()) {
            $query
                ->andWhere('e.state = :state')
                ->setParameter('state', $state);
        }
        return $query
            ->getQuery()
            ->getResult();
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
