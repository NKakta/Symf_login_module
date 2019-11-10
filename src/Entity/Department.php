<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="departments")
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="topic", type="string", length=191, unique=false)
     * @Assert\NotBlank
     */
    private $topic;

    /**
     * @var User[]
     *
     * @ORM\OneToMany(
     *     targetEntity="User",
     *     mappedBy="department",
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
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Department
     */
    public function setId($id): Department
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     * @return Department
     */
    public function setTopic($topic): Department
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @param \DateTime $dateFrom
     * @return Department
     */
    public function setDateFrom(\DateTime $dateFrom): Department
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateTo
     * @return Department
     */
    public function setDateTo(\DateTime $dateTo): Department
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
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
}
