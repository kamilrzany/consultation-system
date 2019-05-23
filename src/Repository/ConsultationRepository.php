<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }

    public function findActiveReservations()
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('c')
            ->where('c.endDate >= :date')
            ->setParameter('date', $date)
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveConsultations($user)
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('c')
            ->innerJoin('c.author', 'u')
            ->addSelect('u')
            ->where('c.author = :author')
            ->AndWhere('c.endDate >= :date')
            ->setParameter('author', $user)
            ->setParameter('date', $date)
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findConsultationsInCurrentWeek($user)
    {
        $firstDay = new \DateTime(date("d.m.Y", strtotime('monday this week')));
        $lastDay = new \DateTime(date("d.m.Y", strtotime('sunday this week')));
        $date = new \DateTime();

        return $this->createQueryBuilder('c')
            ->innerJoin('c.author', 'u')
            ->addSelect('u')
            ->where('c.author = :author')
            ->AndWhere('c.startDate BETWEEN :firstDay AND :lastDay')
            ->AndWhere('c.endDate >= :date')
            ->setParameter('firstDay', $firstDay)
            ->setParameter('lastDay', $lastDay)
            ->setParameter('author', $user)
            ->setParameter('date', $date)
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
