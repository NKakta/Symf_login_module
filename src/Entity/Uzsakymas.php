<?php

namespace App\Entity;

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
}
