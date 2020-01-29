<?php

namespace App\Repository;

use App\Entity\GroupOfPeople;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GroupOfPeople|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupOfPeople|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupOfPeople[]    findAll()
 * @method GroupOfPeople[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupOfPeopleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupOfPeople::class);
    }

    // /**
    //  * @return GroupOfPeople[] Returns an array of GroupOfPeople objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupOfPeople
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
