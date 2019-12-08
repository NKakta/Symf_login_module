<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=191)
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $privacy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $marketing;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $password;

    /**
     * @Assert\Length(
     *      min = 5,
     *      max = 40,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank
     */
    private $roles = null;

    /**
     * @var VacationRequest[]
     *
     * @ORM\OneToMany(
     *     targetEntity="VacationRequest",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $vacationRequests;

    /**
     * @var Vacation[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Vacation",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $vacations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {

        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = array_unique($roles);
        return $this;
    }

    public function setRole(?string $role): self
    {
        $this->roles[] = $role;
        $this->roles = array_unique($this->roles);

        return $this;
    }

    public function getRoleStr()
    {
        return implode(',', $this->roles);
    }

    public function getPublicInfo()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
        ];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        //I'm using bcrypt so no need for salt
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        //No need to erase it. Not storing plainPassword
        //$this->plainPassword = null;
    }

    /**
     * @return VacationRequest[]
     */
    public function getVacationRequests()
    {
        return $this->vacationRequests;
    }

    /**
     * @param VacationRequest[] $vacationRequests
     */
    public function setVacationRequests(array $vacationRequests): void
    {
        $this->vacationRequests = $vacationRequests;
    }

    /**
     * @return Vacation[]
     */
    public function getVacations(): array
    {
        return $this->vacations;
    }

    /**
     * @param Vacation[] $vacations
     */
    public function setVacations(array $vacations): void
    {
        $this->vacations = $vacations;
    }

    /**
     * @return mixed
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param mixed $activated
     * @return User
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param mixed $privacy
     * @return User
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMarketing()
    {
        return $this->marketing;
    }

    /**
     * @param mixed $marketing
     * @return User
     */
    public function setMarketing($marketing)
    {
        $this->marketing = $marketing;
        return $this;
    }


}
