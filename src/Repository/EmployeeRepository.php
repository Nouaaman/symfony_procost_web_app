<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Employee $entity, bool $flush = true): void
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
    public function remove(Employee $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function nbrEmployees(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findAllWithJob(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT e as employee , j.name as job FROM App\Entity\Employee e, App\Entity\Job j WHERE e.idJob = j.id');
        return $query->getResult();
    }

    public function findOneWithJob(int $id): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT e as employee , j.name as job FROM App\Entity\Employee e, App\Entity\Job j WHERE e.id = :id AND e.idJob = j.id')
            ->setParameter('id', $id);
        return $query->getOneOrNullResult();
    }

    public function topEmployee()
    {
    //     $em = $this->getEntityManager();

    //     $rsm = new ResultSetMapping;
    //     $rsm->addEntityResult('App\Entity\Employee', 'e');
    //     $rsm->addEntityResult('App\Entity\ProductionTimes', 'pt');
    //     $rsm->addFieldResult('e', 'id', 'id');
    //     $rsm->addFieldResult('e', 'firstName', 'firstName');
    //     $rsm->addFieldResult('e', 'dailyCost', 'dailyCost');
    //     $rsm->addFieldResult('e', 'hiringDate', 'hiringDate');
    //     // $rsm->addFieldResult('e2', 'sumTimes', 'sumTimes');
    //     // $rsm->addFieldResult('pt', 'productionTime', 'productionTime');

    //     $query = $em->createNativeQuery('SELECT 
    //         e.id, 
    //         e.firstName, 
    //         e.lastName, 
    //         e.dailyCost, 
    //         e.hiringDate, 
    //         e2.sumTimes 
    //     FROM App\Entity\Employee e INNER JOIN ( SELECT e.id, 
    //             SUM(pt.productionTime) AS sumTimes 
    //         FROM 
    //             App\Entity\ProductionTimes pt, 
    //             App\Entity\Employee e 
    //         WHERE 
    //             pt.idImployee = e.id 
    //             GROUP BY e.id 
    //             ORDER BY SUM(pt.productionTime) 
    //             DESC LIMIT 1 ) AS e2 
    //         ON e.id = e2.id', $rsm);
    //     dump($query);

    //     return $query->getResult();
    }

    // private function addJoinJob(QueryBuilder $qb): void
    // {
    //     $qb
    //         ->addSelect('j')
    //         ->innerJoin('e.idJob', 'j');
    // }
    // public function findAll() :Query
    // {
    //     return $this->createQueryBuilder('employee')
    //     ->getQuery();
    // }


    // /**
    //  * @return Employee[] Returns an array of Employee objects
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
    public function findOneBySomeField($value): ?Employee
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
