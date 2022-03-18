<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Project $entity, bool $flush = true): void
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
    public function remove(Project $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function nbrDeliveredProjects(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.deliveryDate IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function nbrCurrentProjects(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.deliveryDate IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function theLatestProjects(): array
    {
        $qb = $this->createQueryBuilder('project')
            ->orderBy('project.creationDate', 'DESC')
            ->setMaxResults(5);
        return $qb->getQuery()->getResult();
    }

    public function currentProjects()
    {
        return $this->createQueryBuilder('p')
            ->where('p.deliveryDate IS NULL');
    }

    public function employeesWorkedOnProject(int $id): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT 
                                        e.firstName, e.lastName, SUM(pt.productionTime) AS totalDays , e.dailyCost
                                   FROM 
                                        App\Entity\Employee e, App\Entity\Project p, App\Entity\ProductionTimes pt
                                   WHERE 
                                        p.id = :idProj AND pt.idEmployee = e.id AND pt.idProject = p.id
                                   GROUP BY 
                                        e.firstName, e.lastName")
            ->setParameter('idProj', $id);
        return $query->getResult();
    }

    public function totalProjects(): int
    {

        return  $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }






    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
