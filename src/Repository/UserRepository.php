<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[] Returns an array of User objects
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findSkillsById($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                 SELECT * from skill
                       JOIN has ON skill.id=has.skill
                       WHERE has.user = :id
         ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
     }

/*
    public function findUsersbyCountry()
        {    return $this->getEntityManager()
                ->createQuery(
                    'SELECT * from skill
                          JOIN has ON skill.id=has.skill
                          WHERE has.user = :id'
                )
                ->setParameter('id', $id);
        }
*/

    /* public function findSkillsById()
    {    return $this->getEntityManager()
            ->createQuery(
                'SELECT * from skill
                      JOIN has ON skill.id=has.skill
                      WHERE has.user = :id'
            )
            ->setParameter('id', $id);
    }
    */

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
