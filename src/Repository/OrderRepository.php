<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Terminal;
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

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByTerminalAndMagicNumber(Terminal $terminal, string $magicNumber): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.terminal_id = :terminal')
            ->setParameter('terminal', $terminal->getId())
            ->andWhere('o.magic_number = :magic')
            ->setParameter('magic', $magicNumber)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
