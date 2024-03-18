<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $plateNumber = null;

    #[ORM\ManyToMany(targetEntity: Fleet::class, inversedBy: 'lstVehicles')]
    private Collection $lstFleets;

    public function __construct()
    {
        $this->lstFleets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $Id): static
    {
        $this->Id = $Id;

        return $this;
    }

    public function getPlateNumber(): ?string
    {
        return $this->plateNumber;
    }

    public function setPlateNumber(?string $plateNumber): static
    {
        $this->plateNumber = $plateNumber;

        return $this;
    }

    /**
     * @return Collection<int, Fleet>
     */
    public function getLstFleets(): Collection
    {
        return $this->lstFleets;
    }

    public function addLstFleet(Fleet $lstFleet): static
    {
        if (!$this->lstFleets->contains($lstFleet)) {
            $this->lstFleets->add($lstFleet);
        }

        return $this;
    }

    public function removeLstFleet(Fleet $lstFleet): static
    {
        $this->lstFleets->removeElement($lstFleet);

        return $this;
    }
}
