<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email has already been used."
 * )
 */
class User implements UserInterface
{
  const SUPER_ADMIN = 'superAdmin';
  const Admin = 'Admin';
  const Manager = 'Manager';
  const Salesman = 'Salesman';
  const Customer = 'Customer';

    const ROLES = [
        self::Admin => self::Admin,
        self::Manager => self::Manager,
        self::Salesman => self::Salesman,
        self::Customer => self::Customer,
        self::SUPER_ADMIN => self::SUPER_ADMIN,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Valid first name is required")
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Valid last name is required")
     */
    private $last_name;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;


    /**
     * @var string The hashed password
     * @Assert\NotBlank(message = "Please enter a valid password")
     * @Assert\Length(max=4096)
     * @ORM\Column(type="string")
     */
    private $password;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): ?string
    {
      return $this->roles;
    }

    public function setRoles(?string $roles): self
    {
      $this->roles = $roles;

      return $this;
    }

    public function getRolesList()
    {
        return self::ROLES;
    }
    public function __toString(){
        return $this->last_name;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
      return $this->password;
    }

    public function setPassword(string $password): self
    {
      $this->password = $password;

      return $this;
    }
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
      return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
      // If you store any temporary, sensitive data on the user, clear it here
      // $this->plainPassword = null;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
      return (string) $this->email;
    }


}
