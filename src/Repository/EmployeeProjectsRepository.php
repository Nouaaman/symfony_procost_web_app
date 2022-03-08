<?php

namespace App\Repository;

use App\Entity\EmployeeProjects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmployeeProjects|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeProjects|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeProjects[]    findAll()
 * @method EmployeeProjects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeProjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeProjects::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EmployeeProjects $entity, bool $flush = true): void
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
    public function remove(EmployeeProjects $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return EmployeeProjects[] Returns an array of EmployeeProjects objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmployeeProjects
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
