<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="pictures")
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture
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
     * @ORM\Column(name="description", type="string", length=255, unique=false)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(name="url", type="string", length=255, unique=false)
     * @Assert\NotBlank
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Contest", inversedBy="pictures", cascade={"remove"})
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="picture_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contest_id", referencedColumnName="id", onDelete="cascade")},
     *     )
     *   )
     */
    private $contests;

    /**
     * @var Comment[]
     *
     *  @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="picture",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $comments;

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Picture
     */
    public function setId(int $id): Picture
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
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
     * @return Collection
     */
    public function getContests(): Collection
    {
        return $this->contests;
    }

    /**
     * @param Collection $contests
     */
    public function setContests(Collection $contests): void
    {
        $this->contests = $contests;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }


}
