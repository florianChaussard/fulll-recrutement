<?php

namespace App\Entity;

use App\Repository\FleetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FleetRepository::class)]
#[ORM\Table(name: 'Fleet')]
class Fleet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\ManyToMany(targetEntity: Vehicle::class, mappedBy: 'lstFleets')]
    private Collection $lstVehicles;

    public function __construct()
    {
        $this->lstVehicles = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getLstVehicles(): Collection
    {
        return $this->lstVehicles;
    }

    public function addToLstVehicle(Vehicle $vehicle): static
    {
        if (!$this->lstVehicles->contains($vehicle)) {
            $this->lstVehicles->add($vehicle);
            $vehicle->addToLstFleet($this);
        }

        return $this;
    }

    public function removeFromLstVehicle(Vehicle $vehicle): static
    {
        if ($this->lstVehicles->removeElement($vehicle)) {
            $vehicle->removeFromLstFleet($this);
        }

        return $this;
    }
}
