<?php

namespace App\Controller\Api;

use App\Repository\OrderRepository;
use App\Repository\TerminalRepository;
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

        $data = $this->parseData($request);

        if (null === $ticker = $data['ticker_symbol'] ?? null) {
            throw new BadRequestException('No ticker_symbol in data');
        }

        $orders = $this->orderRepository->findUnsyncedOrders($terminal, $ticker);

        return new Response('Order created', 201);
    }
}
