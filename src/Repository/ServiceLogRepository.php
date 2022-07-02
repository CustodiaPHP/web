<?php

namespace App\Repository;

use App\Entity\ServiceLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceLog>
 *
 * @method ServiceLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceLog[]    findAll()
 * @method ServiceLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceLog::class);
    }

    public function add(ServiceLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ServiceLog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOldLogs(\DateTime $dateTime): array
    {
        return $this->createQueryBuilder('sl')
            ->andWhere('sl.timestamp < :date')
            ->setParameter('date', $dateTime->format('Y-m-d H:i:s'))
            ->orderBy('sl.timestamp')
            ->getQuery()
            ->getResult() ?? array()
        ;
    }

//    /**
//     * @return ServiceLog[] Returns an array of ServiceLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ServiceLog
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
