<?php
declare(strict_types=1);

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
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_CRAFTSMAN = 'ROLE_CRAFTSMAN';
    const ROLE_USER = 'ROLE_USER';

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
     * @var Order[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Order",
     *     mappedBy="craftsman",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $jobs;

    /**
     * @var Order[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Order",
     *     mappedBy="customer",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private $orders;

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
        $new = [];
        foreach($this->roles as $role) {
            if ($role == 'ROLE_USER') { $new[] = 'Vartotojas';}
            if ($role == 'ROLE_ADMIN') { $new[] = 'Administratorius';}
            if ($role == 'ROLE_CRAFTSMAN') { $new[] = 'Meistras';}
            if ($role == 'ROLE_SUPERADMIN') { $new[] = 'Super Administratorius';}
        }
        return implode(' <br> ', $new);
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
     * @return Order[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @param Order[] $orders
     */
    public function setOrders(array $orders): void
    {
        $this->orders = $orders;
    }

    /**
     * @return Order[]
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    /**
     * @param Order[] $jobs
     */
    public function setJobs(array $jobs): void
    {
        $this->jobs = $jobs;
    }
}
