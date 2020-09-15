<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Terminal;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private TerminalRepository $terminalRepository;

    public function __construct(ManagerRegistry $registry, TerminalRepository $terminalRepository)
    {
        parent::__construct($registry, Order::class);

        $this->terminalRepository = $terminalRepository;
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

    /**
     * @return Order[]
     *
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException
     */
    public function findUnsyncedOrders(Terminal $terminal, string $ticker): array
    {
        if (null === $main = $this->terminalRepository->getMainForTicker($ticker)) {
            throw new BadRequestException('No main terminal found for ticker `' . $ticker . '`');
        }

        $lastSyncAt = $terminal->getLastSyncAt() ?? new DateTimeImmutable();

        return $this->createQueryBuilder('o')
            ->andWhere('o.terminal_id = :terminal')
            ->setParameter('terminal', $main->getId())
            ->andWhere('o.updated_at > :updated_at')
            ->setParameter('updated_at', $lastSyncAt->format('Y-m-d H:i:s'))
            ->andWhere('o.status != :open_error')
            ->setParameter('open_error', Order::STATUS_OPEN_ERROR)
            ->orderBy('o.updated_at', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
