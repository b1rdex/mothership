<?php

namespace App\Repository;

use App\Entity\Terminal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Terminal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terminal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terminal[]    findAll()
 * @method Terminal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerminalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terminal::class);
    }

    // /**
    //  * @return Terminal[] Returns an array of Terminal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByCodeAndTicker(string $code, string $ticker): ?Terminal
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.code = :code')
            ->setParameter('code', $code)
            ->andWhere('t.ticker_symbol = :ticker')
            ->setParameter('ticker', $ticker)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMainByTicker(string $ticker): ?Terminal
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.ticker_symbol = :ticker')
            ->andWhere('t.is_main = :is_main')
            ->setParameter('ticker', $ticker)
            ->setParameter('is_main', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getMainForTicker(string $ticker): ?Terminal
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.ticker_symbol = :ticker')
            ->setParameter('ticker', $ticker)
            ->andWhere('t.is_main = :is_main')
            ->setParameter('is_main', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
