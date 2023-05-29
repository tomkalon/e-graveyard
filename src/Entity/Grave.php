<?php

namespace App\Entity;

use App\Repository\GraveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GraveRepository::class)]
class Grave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sector = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $row = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $paid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $positionX = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $positionY = null;

    #[ORM\ManyToOne(inversedBy: 'graves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Graveyard $graveyard = null;

    #[ORM\OneToMany(mappedBy: 'grave', targetEntity: Person::class, orphanRemoval: false)]
    private Collection $people;

    public function __construct()
    {
        $this->people = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getRow(): ?string
    {
        return $this->row;
    }

    public function setRow(?string $row): self
    {
        $this->row = $row;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    public function getPaid(): ?\DateTimeInterface
    {
        return $this->paid;
    }

    public function setPaid(?\DateTimeInterface $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getPositionX(): ?string
    {
        return $this->positionX;
    }

    public function setPositionX(?string $positionX): self
    {
        $this->positionX = $positionX;

        return $this;
    }

    public function getPositionY(): ?string
    {
        return $this->positionY;
    }

    public function setPositionY(?string $positionY): self
    {
        $this->positionY = $positionY;

        return $this;
    }

    public function getGraveyard(): ?Graveyard
    {
        return $this->graveyard;
    }

    public function setGraveyard(?Graveyard $graveyard): self
    {
        $this->graveyard = $graveyard;

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people->add($person);
            $person->setGrave($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getGrave() === $this) {
                $person->setGrave(null);
            }
        }

        return $this;
    }
}
