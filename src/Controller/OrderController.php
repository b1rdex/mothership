<?php

namespace App\Controller;

use App\Entity\Terminal;
use App\Repository\OrderRepository;
use App\Repository\TerminalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/{ticker?}", name="orders", methods={"GET"})
     */
    public function index(
        OrderRepository $orderRepository,
        TerminalRepository $terminalRepository,
        ?string $ticker = null
    ): Response {
        $tickers = array_map(fn(Terminal $terminal) => $terminal->getTickerSymbol(), $terminalRepository->getMasters());

        $orders = null;
        dd($ticker);


        return $this->render('order/index.html.twig', [
            'tickers' => $tickers,
        ]);
    }
}
