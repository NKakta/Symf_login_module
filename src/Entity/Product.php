<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=191, unique=false)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var User[]
     *
     * @ORM\OneToMany(
     *     targetEntity="User",
     *     mappedBy="product",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $users;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_from", type="datetime")
     */
    private $dateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_to", type="datetime")
     */
    private $dateTo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Uzsakymas", mappedBy="product")
     */
    private $uzsakymas;

    public function __construct()
    {
        $this->uzsakymas = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Product
     */
    public function setId($id): Product
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Product
     */
    public function setName($name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param \DateTime $dateFrom
     * @return Product
     */
    public function setDateFrom(\DateTime $dateFrom): Product
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateFrom(): ?\DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateTo
     * @return Product
     */
    public function setDateTo(\DateTime $dateTo): Product
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo(): ?\DateTime
    {
        return $this->dateTo;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[] $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    /**
     * @return Collection|Uzsakymas[]
     */
    public function getUzsakymas(): Collection
    {
        return $this->uzsakymas;
    }

    public function addUzsakyma(Uzsakymas $uzsakyma): self
    {
        if (!$this->uzsakymas->contains($uzsakyma)) {
            $this->uzsakymas[] = $uzsakyma;
            $uzsakyma->addProduct($this);
        }

        return $this;
    }

    public function removeUzsakyma(Uzsakymas $uzsakyma): self
    {
        if ($this->uzsakymas->contains($uzsakyma)) {
            $this->uzsakymas->removeElement($uzsakyma);
            $uzsakyma->removeProduct($this);
        }

        return $this;
    }
}
