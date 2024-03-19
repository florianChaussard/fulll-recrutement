<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[ORM\Table(name: 'Vehicle')]
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

    #[ORM\ManyToOne(inversedBy: 'lstVehicles')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Location $location = null;

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

    public function addToLstFleet(Fleet $lstFleet): static
    {
        if (!$this->lstFleets->contains($lstFleet)) {
            $this->lstFleets->add($lstFleet);
        }

        return $this;
    }

    public function removeFromLstFleet(Fleet $lstFleet): static
    {
        $this->lstFleets->removeElement($lstFleet);

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }
}
