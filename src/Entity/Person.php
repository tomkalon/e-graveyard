<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Nazwisko musi składać się z co najmniej {{ limit }} znaków!',
        maxMessage: 'Nazwisko musi składać się maksymalnie z {{ limit }} znaków!',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Imię musi składać się z co najmniej {{ limit }} znaków!',
        maxMessage: 'Imię musi składać się maksymalnie z {{ limit }} znaków!',
    )]
    private ?string $surname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $born = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $death = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $edited = null;

    #[ORM\ManyToOne(inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Grave $grave = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $EditedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBorn(): ?\DateTimeInterface
    {
        return $this->born;
    }

    public function setBorn(?\DateTimeInterface $born): void
    {
        $this->born = $born;
    }

    public function getDeath(): ?\DateTimeInterface
    {
        return $this->death;
    }

    public function setDeath(?\DateTimeInterface $death): void
    {
        $this->death = $death;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getEdited(): ?\DateTimeInterface
    {
        return $this->edited;
    }

    public function setEdited(\DateTimeInterface $edited): self
    {
        $this->edited = $edited;

        return $this;
    }

    public function getGrave(): ?Grave
    {
        return $this->grave;
    }

    public function setGrave(?Grave $grave): self
    {
        $this->grave = $grave;

        return $this;
    }

    public function getEditedBy(): ?string
    {
        return $this->EditedBy;
    }

    public function setEditedBy(?string $EditedBy): self
    {
        $this->EditedBy = $EditedBy;

        return $this;
    }
}
