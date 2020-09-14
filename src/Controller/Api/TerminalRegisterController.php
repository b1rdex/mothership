<?php

namespace App\Controller\Api;

use App\Entity\Terminal;
use App\Repository\TerminalRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TerminalRegisterController
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
     * @Route("/api/register", name="api_register", methods="get")
     */
    public function __invoke(Request $request): Response
    {
        [$code, $ticker] = $this->getTerminalCodeSymbol($request);
        $data = $this->parseData($request);

        $mainTerminal = $this->terminalRepository->findMainByTicker($ticker);
        $isMain = $data['is_main'] ?? null;
        if ($isMain && $mainTerminal && $mainTerminal->getCode() !== $code) {
            throw new BadRequestException('Для тикера ' . $ticker . ' уже есть головной терминал');
        }

        if (null === $terminal = $this->terminalRepository->findByCodeAndTicker($code, $ticker)) {
            $terminal = new Terminal();
            $terminal->setCode($code);
            $terminal->setTickerSymbol($ticker);
            $terminal->setCreatedAt(new DateTimeImmutable());
            if (null === $isMain) {
                throw new BadRequestException('Terminal creation requires is_main param to be set');
            }
            $terminal->setIsMain((bool)$isMain);
        }
        $isNew = $terminal->getId() === null;

        if (null !== $description = $data['description'] ?? null) {
            $terminal->setDescription($description);
        }

        if (null !== $balance = $data['balance'] ?? null) {
            $terminal->setBalance($balance);
        }
        if (null !== $freeMargin = $data['free_margin'] ?? null) {
            $terminal->setFreeMargin($freeMargin);
        }

        $terminal->setUpdatedAt(new DateTimeImmutable());

        if (null !== $response = $this->validateEntity($this->validator, $terminal)) {
            return $response;
        }

        $this->entityManager->persist($terminal);
        $this->entityManager->flush();

        return $isNew
            ? new Response('Terminal created', 201)
            : new Response('Terminal updated', 200);
    }
}
