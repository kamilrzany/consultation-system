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

    public function findConsultationInCurrentWeek($user)
    {
        $firstDay = new \DateTime(date("d.m.Y", strtotime('monday this week')));
        $lastDay = new \DateTime(date("d.m.Y", strtotime('sunday this week')));

        return $this->createQueryBuilder('c')
            ->innerJoin('c.author', 'u')
            ->addSelect('u')
            ->where('c.author = :author')
            ->AndWhere('c.startDate BETWEEN :firstDay AND :lastDay')
            ->setParameter('firstDay', $firstDay)
            ->setParameter('lastDay', $lastDay)
            ->setParameter('author', $user)
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
