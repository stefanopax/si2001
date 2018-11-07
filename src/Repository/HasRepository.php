<?php

namespace App\Repository;

use App\Entity\Has;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Has|null find($id, $lockMode = null, $lockVersion = null)
 * @method Has|null findOneBy(array $criteria, array $orderBy = null)
 * @method Has[]    findAll()
 * @method Has[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Has::class);
    }

//    /**
//     * @return Has[] Returns an array of Has objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Has
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
