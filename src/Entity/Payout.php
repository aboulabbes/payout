<?php


namespace App\Entity;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Payout
 * @package App\Entity
 * @ORM\Entity
 */

class Payout
{

    /**
     * @var float
     */
    const CONSTANT = 100.0;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var string
     * @ORM\Column
     */
    private  string $sellerReference;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private  float $amount;

    /**
     * @var string
     * @ORM\Column
     */
    private  string $currency;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */

    private DateTimeImmutable $createAt;

    /**
     * @var Item[]
     * @ORM\OneToMany(targetEntity="Item",cascade={"persist", "remove"},mappedBy="payout")
     */
    private  Collection $items;



    public function __construct()
    {
        $this->createAt = new DateTimeImmutable();
        $this->amount = 0;
        $this->items = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getSellerReference(): string
    {
        return $this->sellerReference;
    }



    /**
     * @param mixed $sellerReference
     */
    public function setSellerReference(string $sellerReference): void
    {
        $this->sellerReference = $sellerReference;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency() :string
    {
        return $this->currency;
    }

    /**
     *
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreateAt(): DateTimeImmutable
    {
        return $this->createAt;
    }

    /**
     * @param DateTimeImmutable $createAt
     */
    public function setCreateAt(DateTimeImmutable $createAt): void
    {
        $this->createAt = $createAt;
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
     * @return Collection
     */
    public function getItems():Collection
    {
        return $this->items;
    }

    /**
     * @param Item[] items
     * @return void
     */
    public function setItems(Collection $items): void
    {
        $this->items = $items;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item) :void
    {
        if ($this->items->contains($item)) {
            return;
        }
        $this->items->add($item);
        $this->setAmount($item->getPriceAmount()+$this->getAmount());
        $item->setPayout($this);

    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item) :void
    {
        if (!$this->items->contains($item)) {
            return;
        }
        $this->items->removeElement($item);
    }
}