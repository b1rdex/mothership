<?php

namespace App\Controller\Api;

use App\Repository\OrderRepository;
use App\Repository\TerminalRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SyncController
{
    use ParseDataTrait;
    use ValidateEntityTrait;

    private EntityManagerInterface $entityManager;
    private TerminalRepository $terminalRepository;
    private OrderRepository $orderRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TerminalRepository $terminalRepository,
        OrderRepository $orderRepository
    ) {
        $this->entityManager = $entityManager;
        $this->terminalRepository = $terminalRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/api/updateSlave", name="api_updateSlave", methods="get")
     */
    public function __invoke(Request $request): Response
    {
        [$code, $ticker] = $this->getTerminalCodeSymbol($request);
        if (null === $terminal = $this->terminalRepository->findByCodeAndTicker($code, $ticker)) {
            throw new BadRequestException('Terminal not found');
        }

        $orders = $this->orderRepository->findUnsyncedOrders($terminal, $ticker);

        $terminal->setLastSyncAt(new DateTimeImmutable());
        $terminal->setUpdatedAt(new DateTimeImmutable());
        $this->entityManager->persist($terminal);
        $this->entityManager->flush();

        $response = [];
        foreach ($orders as $order) {
            $response[] = implode(';', [
                    'command:' . ($order->getStatus() === 'closed' ? 'close' : $order->getStatus()),
                    'magic_number:' . $order->getMagicNumber(),
                    'type:' . $order->getType(),
                    'lots:' . $order->getLots(),
                    'open_price:' . $order->getOpenPrice(),
                    'sl:' . $order->getSl(),
                    'tp:' . $order->getTp(),
                ]) . ';';
        }

        return new Response(implode(\PHP_EOL, $response), 200);
    }
}
