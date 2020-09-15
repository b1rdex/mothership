<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $ticker_symbol;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $magic_number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice(choices={"buy","sell"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Terminal::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $terminal_id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $lots;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank
     */
    private $open_price;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $close_price;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=10, nullable=true)
     */
    private $sl;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=10, nullable=true)
     */
    private $tp;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $swap;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $profit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice(choices={"open", "closed", "open_error"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $error_message;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank
     */
    private $updated_at;

    const STATUS_CLOSED = 'closed';
    const STATUS_OPEN = 'open';
    const STATUS_OPEN_ERROR = 'open_error';

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
