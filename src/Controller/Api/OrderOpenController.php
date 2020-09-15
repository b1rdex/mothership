<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Repository\TerminalRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderOpenController
{
    use ParseDataTrait;
    use ValidateEntityTrait;

    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;
    private TerminalRepository $terminalRepository;

    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        TerminalRepository $terminalRepository
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->terminalRepository = $terminalRepository;
    }

    /**
     * @Route("/api/orderOpen{kind}", name="api_orderOpen", methods="get", requirements={"kind"="Master|Slave"})
     */
    public function __invoke(Request $request, string $kind): Response
    {
        [$code, $ticker] = $this->getTerminalCodeSymbol($request);
        if (null === $terminal = $this->terminalRepository->findByCodeAndTicker($code, $ticker)) {
            throw new BadRequestException('Terminal not found');
        }

        $isMaster = $kind === 'Master';
        if ($isMaster && !$terminal->getIsMain()) {
            throw new BadRequestException('Wrong endpoint (is_main mismatch)');
        }

        $order = new Order();

        $data = $this->parseData($request);

        if (null !== $tickerSymbol = $data['ticker_symbol'] ?? null) {
            $order->setTickerSymbol($tickerSymbol);
        }
        if (null !== $magicNumber = $data['magic_number'] ?? null) {
            $order->setMagicNumber($magicNumber);
        }
        if (null !== $type = $data['type'] ?? null) {
            $order->setType($type);
        }
        if (null !== $openPrice = $data['open_price'] ?? null) {
            $order->setOpenPrice($openPrice);
        }
        if (null !== $sl = $data['sl'] ?? null) {
            $order->setSl($sl);
        }
        if (null !== $tp = $data['tp'] ?? null) {
            $order->setTp($tp);
        }
        if (null !== $lots = $data['lots'] ?? null) {
            $order->setLots($lots);
        }
        if (null !== $status = $data['status'] ?? null) {
            // Order::STATUS_OPEN || Order::STATUS_OPEN_ERROR
            $order->setStatus($status);
        }
        if (null !== $errorMessage = $data['error_message'] ?? null) {
            $order->setErrorMessage($errorMessage);
        }

        $order->setCreatedAt(new DateTimeImmutable());
        $order->setUpdatedAt(new DateTimeImmutable());

        if (null !== $response = $this->validateEntity($this->validator, $order)) {
            return $response;
        }

        $order->setTerminal($terminal);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new Response('Order created', 201);
    }
}
