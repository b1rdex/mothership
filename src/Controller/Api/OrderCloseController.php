<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Terminal;
use App\Repository\OrderRepository;
use App\Repository\TerminalRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderCloseController
{
    use ParseDataTrait;
    use ValidateEntityTrait;

    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;
    private TerminalRepository $terminalRepository;
    private OrderRepository $orderRepository;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        TerminalRepository $terminalRepository,
        OrderRepository $orderRepository
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->terminalRepository = $terminalRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/api/orderClose", name="api_orderClose", methods="get")
     */
    public function __invoke(Request $request): Response
    {
        $data = $this->parseData($request);

        $code = $data['terminal_code'] ?? null;
        if (!is_string($code) || null === $terminal = $this->terminalRepository->findByCode($code)) {
            throw new BadRequestException('No terminal_code in data or terminal not found');
        }

        $magicNumber = $data['magic_number'] ?? null;
        if (!is_string($magicNumber) || null === $order = $this->orderRepository->findByMagicNumber($magicNumber)) {
            throw new BadRequestException('No magic_number in data or order not found');
        }

        if (null !== $closePrice = $data['close_price'] ?? null) {
            $order->setClosePrice($closePrice);
        }
        if (null !== $swap = $data['swap'] ?? null) {
            $order->setSwap($swap);
        }
        if (null !== $profit = $data['profit'] ?? null) {
            $order->setProfit($profit);
        }
        if (null !== $errorMessage = $data['error_message'] ?? null) {
            $order->setErrorMessage($errorMessage);
        }

        $order->setStatus('closed');
        $order->setUpdatedAt(new DateTimeImmutable());

        if (null !== $response = $this->validateEntity($this->validator, $order)) {
            return $response;
        }

        $order->setTerminal($terminal);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new Response('Order closed', 201);
    }
}
