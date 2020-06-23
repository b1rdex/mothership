<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order[]
     */
    public function findForReplication(?int $sinceId = null): array
    {
        $qb = $this->createQueryBuilder('o')
            ->andWhere('o.is_master = :true')
            ->setParameter('true', true)
            ->orderBy('o.id', 'ASC');

        if ($sinceId) {
            $qb->andWhere('o.id > :id');
            $qb->setParameter(':id', $sinceId);
        }

        return $qb->getQuery()->getResult();
    }
}
