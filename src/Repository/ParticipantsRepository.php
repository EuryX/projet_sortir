<?php

namespace App\Repository;

use App\Entity\Participants;
use App\Entity\Sites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * @method Participants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participants[]    findAll()
 * @method Participants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantsRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participants::class);
    }


    public function loadUserByUsername($username)
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.nickname = :username OR u.mail = :email')
                ->setParameter('username', $username)
                ->setParameter('email', $username)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }



   ///**
   // * @return Participants[] Returns an array of Participants objects
   // */

   /* public function findByUserId($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin(Sites::class,'s',Join::WITH,'p.site = s.id')
            ->andWhere('p.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }*/


    /*
    public function findOneBySomeField($value): ?Participants
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
