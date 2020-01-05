<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ads")
 * @ORM\Entity(repositoryClass="App\Repository\AdRepository")
 */
class Ad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=191, unique=false)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(name="content", type="string", length=255, unique=false)
     * @Assert\NotBlank
     */
    private $content;

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
     * @var string
     *
     * @ORM\Column(name="ip_from", type="string")
     */
    private $ipFrom;

    /**
     * @var int
     *
     * @ORM\Column(name="ip_from_number", type="bigint", nullable=true)
     */
    private $ipFromNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_to", type="string")
     */
    private $ipTo;

    /**
     * @var int
     *
     * @ORM\Column(name="ip_to_number", type="bigint", nullable=true)
     */
    private $ipToNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=true)
     */
    private $confirmed;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Ad
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Ad
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Ad
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @param \DateTime $dateFrom
     * @return Ad
     */
    public function setDateFrom(\DateTime $dateFrom): ?Ad
    {
        $this->dateFrom = $dateFrom;
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
     * @param \DateTime $dateTo
     * @return Ad
     */
    public function setDateTo(\DateTime $dateTo): ?Ad
    {
        $this->dateTo = $dateTo;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpFrom(): ?string
    {
        return $this->ipFrom;
    }

    /**
     * @param string $ipFrom
     * @return Ad
     */
    public function setIpFrom(string $ipFrom): ?Ad
    {
        $this->ipFrom = $ipFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpTo(): ?string
    {
        return $this->ipTo;
    }

    /**
     * @param string $ipTo
     * @return Ad
     */
    public function setIpTo(string $ipTo): ?Ad
    {
        $this->ipTo = $ipTo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     * @return Ad
     */
    public function setConfirmed(bool $confirmed): ?Ad
    {
        $this->confirmed = $confirmed;
        return $this;
    }

    /**
     * @return int
     */
    public function getIpFromNumber(): ?int
    {
        return $this->ipFromNumber;
    }

    /**
     * @param int $ipFromNumber
     * @return Ad
     */
    public function setIpFromNumber(int $ipFromNumber): ?Ad
    {
        $this->ipFromNumber = $ipFromNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getIpToNumber(): ?int
    {
        return $this->ipToNumber;
    }

    /**
     * @param int $ipToNumber
     * @return Ad
     */
    public function setIpToNumber(int $ipToNumber): ?Ad
    {
        $this->ipToNumber = $ipToNumber;
        return $this;
    }
}
