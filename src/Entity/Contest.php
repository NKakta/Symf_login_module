<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="contests")
 * @ORM\Entity(repositoryClass="App\Repository\ContestRepository")
 */
class Contest
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
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Picture", mappedBy="contests", cascade={"persist"})
     */
    private $pictures;

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
     */
    public function setId($id): Contest
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
     */
    public function setTopic($topic): Contest
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @param \DateTime $dateFrom
     * @return Contest
     */
    public function setDateFrom(\DateTime $dateFrom): Contest
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
     * @return Contest
     */
    public function setDateTo(\DateTime $dateTo): Contest
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
}
