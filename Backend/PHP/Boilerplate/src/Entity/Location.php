<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'Location')]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Id = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Vehicle::class, mappedBy: 'location')]
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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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
            $vehicle->setLocation($this);
        }

        return $this;
    }

    public function removeFromLstVehicle(Vehicle $vehicle): static
    {
        if ($this->lstVehicles->removeElement($vehicle)) {
            // set the owning side to null (unless already changed)
            if ($vehicle->getLocation() === $this) {
                $vehicle->setLocation(null);
            }
        }

        return $this;
    }
}
