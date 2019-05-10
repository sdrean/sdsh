<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptRepository")
 */
class Receipt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PurchaseType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $purchaseType;

    /**
     * @ORM\Column(type="float")
     */
    private $Amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $PurchaseDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function setPurchaseType(PurchaseType $purchaseType):self
    {
        $this->purchaseType = $purchaseType;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->PurchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $PurchaseDate): self
    {
        $this->PurchaseDate = $PurchaseDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * @param mixed $purchase
     */
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    }
}
