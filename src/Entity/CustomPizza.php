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
     * @ORM\ManyToOne(targetEntity="App\Entity\FinalOrder", inversedBy="customPizzas")
     */
    private $orderId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ham;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $chicken;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pepperoni;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sweetcorn;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tomato;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $peppers;

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

    public function getOrderId(): ?FinalOrder
    {
        return $this->orderId;
    }

    public function setOrderId(?FinalOrder $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getHam(): ?bool
    {
        return $this->ham;
    }

    public function setHam(?bool $ham): self
    {
        $this->ham = $ham;

        return $this;
    }

    public function getChicken(): ?bool
    {
        return $this->chicken;
    }

    public function setChicken(?bool $chicken): self
    {
        $this->chicken = $chicken;

        return $this;
    }

    public function getPepperoni(): ?bool
    {
        return $this->pepperoni;
    }

    public function setPepperoni(?bool $pepperoni): self
    {
        $this->pepperoni = $pepperoni;

        return $this;
    }

    public function getSweetcorn(): ?bool
    {
        return $this->sweetcorn;
    }

    public function setSweetcorn(?bool $sweetcorn): self
    {
        $this->sweetcorn = $sweetcorn;

        return $this;
    }

    public function getTomato(): ?bool
    {
        return $this->tomato;
    }

    public function setTomato(?bool $tomato): self
    {
        $this->tomato = $tomato;

        return $this;
    }

    public function getPeppers(): ?bool
    {
        return $this->peppers;
    }

    public function setPeppers(?bool $peppers): self
    {
        $this->peppers = $peppers;

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
