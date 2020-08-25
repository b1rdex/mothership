<?php

namespace App\Entity;

use App\Repository\TerminalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TerminalRepository::class)
 */
class Terminal
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
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $last_sync_at;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $balance;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $free_margin;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     */
    private $is_main;

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

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="terminal_id", orphanRemoval=true)
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLastSyncAt(): ?\DateTimeImmutable
    {
        return $this->last_sync_at;
    }

    public function setLastSyncAt(?\DateTimeImmutable $last_sync_at): self
    {
        $this->last_sync_at = $last_sync_at;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getFreeMargin(): ?string
    {
        return $this->free_margin;
    }

    public function setFreeMargin(string $free_margin): self
    {
        $this->free_margin = $free_margin;

        return $this;
    }

    public function getIsMain(): ?bool
    {
        return $this->is_main;
    }

    public function setIsMain(bool $is_main): self
    {
        $this->is_main = $is_main;

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

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setTerminalId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getTerminalId() === $this) {
                $order->setTerminalId(null);
            }
        }

        return $this;
    }
}
