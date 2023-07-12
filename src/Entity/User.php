<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'EMAIL_ALREADY_EXIST')]
#[UniqueEntity(fields: ['username'], message: 'USERNAME_ALREADY_EXIST')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'EditedBy', targetEntity: Grave::class)]
    private Collection $graves;

    #[ORM\OneToMany(mappedBy: 'EditedBy', targetEntity: Person::class)]
    private Collection $people;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->graves = new ArrayCollection();
        $this->people = new ArrayCollection();
    }

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        if ($this->isVerified()) {
            $roles[] = 'ROLE_MANAGER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection<int, Grave>
     */
    public function getGraves(): Collection
    {
        return $this->graves;
    }

    public function addGrave(Grave $grave): static
    {
        if (!$this->graves->contains($grave)) {
            $this->graves->add($grave);
            $grave->setEditedBy($this);
        }

        return $this;
    }

    public function removeGrave(Grave $grave): static
    {
        if ($this->graves->removeElement($grave)) {
            // set the owning side to null (unless already changed)
            if ($grave->getEditedBy() === $this) {
                $grave->setEditedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): static
    {
        if (!$this->people->contains($person)) {
            $this->people->add($person);
            $person->setEditedBy($this);
        }

        return $this;
    }

    public function removePerson(Person $person): static
    {
        if ($this->people->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getEditedBy() === $this) {
                $person->setEditedBy(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
