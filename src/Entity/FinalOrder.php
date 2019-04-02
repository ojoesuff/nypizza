<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FinalOrderRepository")
 */
class FinalOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLine1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLine2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine3;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $county;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $eircode;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $orderStatus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="orderId")
     */
    private $productId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     */
    private $customerId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomPizza", mappedBy="orderId")
     */
    private $customPizzas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FinalOrder", mappedBy="customPizzaId")
     */
    private $orders;

    public function __construct()
    {
        $this->productId = new ArrayCollection();
        $this->customPizzas = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getAddressLine3(): ?string
    {
        return $this->addressLine3;
    }

    public function setAddressLine3(?string $addressLine3): self
    {
        $this->addressLine3 = $addressLine3;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getEircode(): ?string
    {
        return $this->eircode;
    }

    public function setEircode(?string $eircode): self
    {
        $this->eircode = $eircode;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * @return Collection|product[]
     */
    public function getProductId(): Collection
    {
        return $this->productId;
    }

    public function addProductId(product $productId): self
    {
        if (!$this->productId->contains($productId)) {
            $this->productId[] = $productId;
            $productId->setOrderId($this);
        }

        return $this;
    }

    public function removeProductId(product $productId): self
    {
        if ($this->productId->contains($productId)) {
            $this->productId->removeElement($productId);
            // set the owning side to null (unless already changed)
            if ($productId->getOrderId() === $this) {
                $productId->setOrderId(null);
            }
        }

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getCustomerId(): ?User
    {
        return $this->customerId;
    }

    public function setCustomerId(?User $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return Collection|CustomPizza[]
     */
    public function getCustomPizzas(): Collection
    {
        return $this->customPizzas;
    }

    public function addCustomPizza(CustomPizza $customPizza): self
    {
        if (!$this->customPizzas->contains($customPizza)) {
            $this->customPizzas[] = $customPizza;
            $customPizza->setOrderId($this);
        }

        return $this;
    }

    public function removeCustomPizza(CustomPizza $customPizza): self
    {
        if ($this->customPizzas->contains($customPizza)) {
            $this->customPizzas->removeElement($customPizza);
            // set the owning side to null (unless already changed)
            if ($customPizza->getOrderId() === $this) {
                $customPizza->setOrderId(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|FinalOrder[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(FinalOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomPizzaId($this);
        }

        return $this;
    }

    public function removeOrder(FinalOrder $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getCustomPizzaId() === $this) {
                $order->setCustomPizzaId(null);
            }
        }

        return $this;
    }
}
