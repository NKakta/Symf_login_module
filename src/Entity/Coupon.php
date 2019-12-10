<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponRepository")
 */
class Coupon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

//    /**
//     * @var User
//     *
//     * @ORM\OneToOne(targetEntity="App\Entity\Order", inversedBy="coupon")
//     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
//     */
//    private $order;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(?int $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->Value;
    }

    public function setValue(?int $Value): self
    {
        $this->Value = $Value;

        return $this;
    }

    public function getValidDate(): ?\DateTimeInterface
    {
        return $this->validDate;
    }

    public function setValidDate(?\DateTimeInterface $validDate): self
    {
        $this->validDate = $validDate;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

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
}
