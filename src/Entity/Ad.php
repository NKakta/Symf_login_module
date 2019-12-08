<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $info;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $webPage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imgSource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getWebPage(): ?string
    {
        return $this->webPage;
    }

    public function setWebPage(string $webPage): self
    {
        $this->webPage = $webPage;

        return $this;
    }

    public function getImgSource(): ?string
    {
        return $this->imgSource;
    }

    public function setImgSource(string $imgSource): self
    {
        $this->imgSource = $imgSource;

        return $this;
    }
}
