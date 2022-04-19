<?php

namespace App\Repository;

use App\Entity\Progress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Progress|null find($id, $lockMode = null, $lockVersion = null)
 * @method Progress|null findOneBy(array $criteria, array $orderBy = null)
 * @method Progress[]    findAll()
 * @method Progress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Progress::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Progress $entity, bool $flush = true): void
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
    public function remove(Progress $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Progress[] Returns an array of Progress objects
    //  */
    
    public function groupByFormationEnCours($user): ?array
    {
        return $this->createQueryBuilder('p')
            ->setParameter('val', $user)
            ->andWhere('p.user = :val')
            ->andWhere('p.coursFinished = 1')
            ->andWhere('p.formationFinished = 0')
            ->groupBy('p.formation')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }
    public function groupByFormation($user): ?array
    {
        return $this->createQueryBuilder('p')
            ->setParameter('val', $user)
            ->andWhere('p.user = :val')
            ->groupBy('p.formation')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }
    public function groupByFormationFinished($user): ?array
    {
        return $this->createQueryBuilder('p')
            ->setParameter('val', $user)
            ->andWhere('p.user = :val')
            ->andWhere('p.formationFinished = 1')
            ->groupBy('p.formation')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();
    }



    public function groupByFormationsEnCours($user, $formation)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :val')
            ->setParameter('val', $user)
            ->andWhere('p.formation = :formation')
            ->setParameter('formation', $formation)
            ->groupBy('p.formation')
            ->getQuery()
            ->getResult();
    }
    public function findFormationsEnCours($user)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM progress p
            WHERE p.user = :user
            AND p.coursFinished = 1
            ORDER BY p.user ASC
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['user' => $user]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    
    public function f($user, $cours): ?Progress
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :val')
            ->setParameter('val', $user)
            ->andWhere('p.cours = :val')
            ->setParameter('val', $cours)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
