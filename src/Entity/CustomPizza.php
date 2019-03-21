<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomPizzaRepository")
 */
class CustomPizza
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\order", inversedBy="customPizzas")
     */
    private $orderId;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $topping1;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $topping2;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $topping3;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $topping4;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $topping5;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $topping6;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?order
    {
        return $this->orderId;
    }

    public function setOrderId(?order $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getTopping1(): ?string
    {
        return $this->topping1;
    }

    public function setTopping1(?string $topping1): self
    {
        $this->topping1 = $topping1;

        return $this;
    }

    public function getTopping2(): ?string
    {
        return $this->topping2;
    }

    public function setTopping2(?string $topping2): self
    {
        $this->topping2 = $topping2;

        return $this;
    }

    public function getTopping3(): ?string
    {
        return $this->topping3;
    }

    public function setTopping3(?string $topping3): self
    {
        $this->topping3 = $topping3;

        return $this;
    }

    public function getTopping4(): ?string
    {
        return $this->topping4;
    }

    public function setTopping4(?string $topping4): self
    {
        $this->topping4 = $topping4;

        return $this;
    }

    public function getTopping5(): ?string
    {
        return $this->topping5;
    }

    public function setTopping5(?string $topping5): self
    {
        $this->topping5 = $topping5;

        return $this;
    }

    public function getTopping6(): ?string
    {
        return $this->topping6;
    }

    public function setTopping6(?string $topping6): self
    {
        $this->topping6 = $topping6;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
