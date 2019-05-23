<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findReservationsInCurrentWeek($user)
    {
        $firstDay = new \DateTime(date("d.m.Y", strtotime('monday this week')));
        $lastDay = new \DateTime(date("d.m.Y", strtotime('sunday this week')));

        return $this->createQueryBuilder('r')
            ->innerJoin('r.author', 'u')
            ->innerJoin('r.consultation', 'c')
            ->addSelect('u')
            ->addSelect('c')
            ->where('r.author = :author')
            ->AndWhere('c.startDate BETWEEN :firstDay AND :lastDay')
            ->setParameter('firstDay', $firstDay)
            ->setParameter('lastDay', $lastDay)
            ->setParameter('author', $user)
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
