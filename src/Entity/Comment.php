<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="content", type="string", length=191, unique=false)
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @var Picture
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Picture", inversedBy="comments")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     */
    private $plant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
    public function setId($id): Comment
    {
        $this->id = $id;

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
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Picture
     */
    public function getPlant(): Picture
    {
        return $this->plant;
    }

    /**
     * @param Picture $plant
     */
    public function setPlant(Picture $plant): void
    {
        $this->plant = $plant;
    }

}
