<?php

namespace App\Entity;

use App\Repository\GraveyardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GraveyardRepository::class)]
class Graveyard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'graveyard', targetEntity: Grave::class, orphanRemoval: true)]
    private Collection $graves;

    public function __construct()
    {
        $this->graves = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Grave>
     */
    public function getGraves(): Collection
    {
        return $this->graves;
    }

    public function addGrave(Grave $grave): self
    {
        if (!$this->graves->contains($grave)) {
            $this->graves->add($grave);
            $grave->setGraveyard($this);
        }

        return $this;
    }

    public function removeGrave(Grave $grave): self
    {
        if ($this->graves->removeElement($grave)) {
            // set the owning side to null (unless already changed)
            if ($grave->getGraveyard() === $this) {
                $grave->setGraveyard(null);
            }
        }

        return $this;
    }
}
