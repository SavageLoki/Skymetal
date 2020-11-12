<?php

namespace App\Repository;

use App\Entity\BasicUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BasicUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasicUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasicUser[]    findAll()
 * @method BasicUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasicUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasicUser::class);
    }

    // /**
    //  * @return BasicUser[] Returns an array of BasicUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BasicUser
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
