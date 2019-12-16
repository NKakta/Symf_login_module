<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UzsakymasRepository")
 */
class Uzsakymas
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $patvirtinimo_laiskas;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeris;

    /**
     * @ORM\Column(type="integer")
     */
    private $bendra_suma;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statusas;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="uzsakymas")
     */
    private $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatvirtinimoLaiskas(): ?bool
    {
        return $this->patvirtinimo_laiskas;
    }

    public function setPatvirtinimoLaiskas(bool $patvirtinimo_laiskas): self
    {
        $this->patvirtinimo_laiskas = $patvirtinimo_laiskas;

        return $this;
    }

    public function getNumeris(): ?int
    {
        return $this->numeris;
    }

    public function setNumeris(int $numeris): self
    {
        $this->numeris = $numeris;

        return $this;
    }

    public function getBendraSuma(): ?int
    {
        return $this->bendra_suma;
    }

    public function setBendraSuma(int $bendra_suma): self
    {
        $this->bendra_suma = $bendra_suma;

        return $this;
    }

    public function getStatusas(): ?string
    {
        return $this->statusas;
    }

    public function setStatusas(string $statusas): self
    {
        $this->statusas = $statusas;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
        }

        return $this;
    }
}
