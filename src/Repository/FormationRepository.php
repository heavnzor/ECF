<?php

namespace App\Repository;

use App\Entity\Cours;
use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Formation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Formation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Formation[] Returns an array of Formation objects
    //  */

 

    public function findAllByUserId($value)
    {
        return $this->createQueryBuilder('f')
            ->setParameter('val', $value)
            ->andWhere('f.apprenants = :val')
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }


    public function findOneByFormationId($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findOneByUserId($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.auteur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllFormationsOrderById()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findAllOrderByLearnState()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.learnState', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }
}
