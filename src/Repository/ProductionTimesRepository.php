<?php

namespace App\Repository;

use App\Entity\ProductionTimes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductionTimes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionTimes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionTimes[]    findAll()
 * @method ProductionTimes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionTimesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionTimes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ProductionTimes $entity, bool $flush = true): void
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
    public function remove(ProductionTimes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function productionDays(): int|null
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.productionTime)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllByEmployee(int $id): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT p.name, pt.productionTime , pt.entryDate , e.dailyCost
                                   FROM App\Entity\Employee e, App\Entity\Project p, App\Entity\ProductionTimes pt
                                   WHERE e.id = :idEmp AND pt.idEmployee = e.id AND pt.idProject = p.id
                                   ORDER BY pt.entryDate DESC')
            ->setParameter('idEmp', $id);
        return $query->getResult();
    }

    public function latestProductionTimes(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT e.id AS idEmployee, e.firstName, e.lastName, p.id AS idProject, p.name, pt.productionTime
                                   FROM App\Entity\Employee e, App\Entity\Project p, App\Entity\ProductionTimes pt
                                   WHERE pt.idEmployee = e.id AND pt.idProject = p.id
                                   ORDER BY pt.entryDate DESC')
            ->setMaxResults(5);
        return $query->getResult();
    }



    // /**
    //  * @return ProductionTimes[] Returns an array of ProductionTimes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductionTimes
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
