<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
	
    public function findDynamic($userId, $campusId, $params): array
    {
	    $query = $this->createQueryBuilder('e')
		->andWhere('e.campus = :val')
		->setParameter('val', $campusId);

		//$query->andWhere($query->expr()->like('e.name', $query->expr()->literal('%' . ':chaine'. '%')))
		 //->setParameter('chaine', $params->get('searchWord'));

	    if (!empty($params->get('date1'))) {
		    $query->andWhere($query->expr()->gte('e.beginDate', ':dateDebut'))
	    	->setParameter('dateDebut', $params->get('date1'));
	    }

	    if (!empty($params->get('date2'))) {
		    $query->andWhere($query->expr()->lte('e.beginDate', ':dateFin'))
	    	->setParameter('dateFin', $params->get('date2'));
	    }

	    if ($params->get('isOrganizer') != null)
	    {
		    $query->andWhere('e.organizer = :orgaId')
	    		->setParameter('orgaId', $userId);
	    }

	    if ($params->get('isRegistered') != null)
	    {
		
	    }

	    if ($params->get('isMember') != null)
	    {

	    }

	    if ($params->get('pastEvent') != null)
	    {
		$query->andWhere($query->expr()->gt('e.beginDate', 'CURRENT_DATE()'));
	    }

	    return $query->getQuery()->getResult();
    }

//    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
