<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Item
 * @package App\Entity
 * @ORM\Entity
 */

class Item
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column
     */
    private string $item;

    /**
     * @var string
     * @ORM\Column
     */
    private string $priceCurrency;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private float $priceAmount;

    /**
     * @var string
     * @ORM\Column
     */
    private string $sellerReference;


    /**
     * @var Payout
     * @ORM\ManyToOne(targetEntity="Payout",inversedBy="items")
     */

    private Payout $payout;

    /**
     * @return string
     */
    public function getItem(): string
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem(string $item): void
    {
        $this->item = $item;
    }

    /**
     * @return string
     */
    public function getPriceCurrency(): string
    {
        return $this->priceCurrency;
    }

    /**
     * @param mixed $priceCurrency
     */
    public function setPriceCurrency(string $priceCurrency): void
    {
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return float
     */
    public function getPriceAmount(): float
    {
        return $this->priceAmount;
    }

    /**
     * @param float $priceAmount
     */
    public function setPriceAmount(float $priceAmount): void
    {
        $this->priceAmount = $priceAmount;
    }

    /**
     * @return string
     */
    public function getSellerReference(): string
    {
        return $this->sellerReference;
    }

    /**
     * @param string $sellerReference
     */
    public function setSellerReference(string $sellerReference): void
    {
        $this->sellerReference = $sellerReference;
    }


    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @param Payout $payout
     */
    public function setPayout(Payout $payout): void
    {
        $this->payout = $payout;
    }
}