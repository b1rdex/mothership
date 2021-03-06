<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ExpectedValues;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    public const STATUS_CLOSED = 'closed';
    public const STATUS_OPEN = 'open';
    public const STATUS_OPEN_ERROR = 'open_error';

    public const TYPE_BUY = 'buy';
    public const TYPE_SELL = 'sell';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $ticker_symbol;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $magic_number;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice(choices={Order::TYPE_BUY, Order::TYPE_SELL})
     */
    private ?string $type;
    /**
     * @ORM\ManyToOne(targetEntity=Terminal::class, inversedBy="orders", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Terminal $terminal_id = null;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private ?int $lots;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank
     */
    private ?string $open_price;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $close_price;
    /**
     * @ORM\Column(type="decimal", precision=20, scale=10, nullable=true)
     */
    private ?string $sl;
    /**
     * @ORM\Column(type="decimal", precision=20, scale=10, nullable=true)
     */
    private ?string $tp;
    /**
     * @ORM\Column(type="decimal", precision=20, scale=10, nullable=true)
     */
    private ?string $lot_size;
    /**
     * @ORM\Column(type="decimal", precision=20, scale=10, nullable=true)
     */
    private ?string $tick_size;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $swap;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private ?string $profit;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice(choices={Order::STATUS_OPEN, Order::STATUS_CLOSED, Order::STATUS_OPEN_ERROR})
     */
    private ?string $status;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $error_message;
    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     */
    private ?DateTimeImmutable $created_at;
    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     */
    private ?DateTimeImmutable $updated_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTickerSymbol(): ?string
    {
        return $this->ticker_symbol;
    }

    public function setTickerSymbol(string $ticker_symbol): self
    {
        $this->ticker_symbol = $ticker_symbol;

        return $this;
    }

    public function getMagicNumber(): ?string
    {
        return $this->magic_number;
    }

    public function setMagicNumber(string $magic_number): self
    {
        $this->magic_number = $magic_number;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    #[ExpectedValues(values: [self::TYPE_BUY, self::TYPE_SELL])]
    /**
     * @phpstan-param self::TYPE_* $type
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTerminal(): ?Terminal
    {
        return $this->terminal_id;
    }

    public function setTerminal(?Terminal $terminal): self
    {
        $this->terminal_id = $terminal;

        return $this;
    }

    public function getLots(): ?int
    {
        return $this->lots;
    }

    public function setLots(int $lots): self
    {
        $this->lots = $lots;

        return $this;
    }

    public function getOpenPrice(): ?string
    {
        return $this->open_price;
    }

    public function setOpenPrice(string $open_price): self
    {
        $this->open_price = $open_price;

        return $this;
    }

    public function getClosePrice(): ?string
    {
        return $this->close_price;
    }

    public function setClosePrice(?string $close_price): self
    {
        $this->close_price = $close_price;

        return $this;
    }

    public function getSl(): ?string
    {
        return $this->sl;
    }

    public function setSl(?string $sl): self
    {
        $this->sl = $sl;

        return $this;
    }

    public function getTp(): ?string
    {
        return $this->tp;
    }

    public function setTp(?string $tp): self
    {
        $this->tp = $tp;

        return $this;
    }

    public function getLotSize(): ?string
    {
        return $this->lot_size;
    }

    public function setLotSize(?string $lotSize): self
    {
        $this->lot_size = $lotSize;

        return $this;
    }

    public function getTickSize(): ?string
    {
        return $this->tick_size;
    }

    public function setTickSize(?string $tickSize): self
    {
        $this->tick_size = $tickSize;

        return $this;
    }

    public function getSwap(): ?string
    {
        return $this->swap;
    }

    public function setSwap(?string $swap): self
    {
        $this->swap = $swap;

        return $this;
    }

    public function getProfit(): ?string
    {
        return $this->profit;
    }

    public function setProfit(?string $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    #[ExpectedValues(values: [self::STATUS_OPEN, self::STATUS_CLOSED, self::STATUS_OPEN_ERROR])]
    /**
     * @phpstan-param self::STATUS_* $status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->error_message;
    }

    public function setErrorMessage(?string $error_message): self
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
