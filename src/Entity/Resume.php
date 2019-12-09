<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="resumes")
 * @ORM\Entity(repositoryClass="App\Repository\ResumeRepository")
 */
class Resume
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="resumes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $area;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $education;


    /**
     * @ORM\Column(type="float")
     */
    private $salary;

    /**
     * @ORM\Column(type="integer")
     */
    private $experience;

    /**
     * @ORM\Column(type="array")
     */
    private $languages = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMain;


    /**
     * @ORM\Column(type="text")
     */
    private $aboutYou;

    /**
     * @ORM\Column(type="array", nullable=true)
     *
     */
    private $usersToHide;


    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Resume
     */
    public function setId(int $id): Resume
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUserMain(): ?int
    {
        return $this->user->getMainNum();
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
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(string $education): self
    {
        $this->education = $education;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getLanguages(): ?array
    {
        return $this->languages;
    }

    public function getKalbosStr()
    {
        return implode(',', $this->languages);
    }

    public function setLanguages(array $languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    public function getIsMain(): ?bool
    {
        return $this->isMain;
    }

    public function setIsMain(bool $isMain): self
    {
        $this->isMain = $isMain;

        return $this;
    }


    public function getAboutYou(): ?string
    {
        return $this->aboutYou;
    }

    public function setAboutYou(string $aboutYou): self
    {
        $this->aboutYou = $aboutYou;

        return $this;
    }

    public function getUsersToHide(): ?array
    {
        return $this->usersToHide;
    }

    public function setUsersToHide(?array $usersToHide): self
    {
        $this->usersToHide = $usersToHide;

        return $this;
    }





}
