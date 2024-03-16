<?php

namespace Fulll\Domain;

Class Vehicle{
    /** @var string an unique identifier for the vehicle */
    private string $id;
    /** @var string type the vehicle's type (car, moto, plane, donkey...)*/
    private string $type;
    /** @var array The list of fleets that the vehicle is in */
    private array $lstFleets;

    /** @var Location $location the place where the vehicle is parked  */
    private Location $location;

    public function __construct(string $type = '')
    {
        $this->id = uniqid();
        $this->type = $type;
        $this->lstFleets = [];
        $this->location = new Location();
    }

    public function getId():string
    {
        return $this->id;
    }

    public function getType():string
    {
        return $this->type;
    }

    public function setType(string $type):self{
        $this->type = $type;
        return $this;
    }

    public function getLstFleets():array
    {
        return $this->lstFleets;
    }

    /**
     * Adds the vehicle to a fleet if it is not already part of it
     * Returns true if the vehicle was added to the fleet, returns false if the vehicle was already part of the given fleet
     * @param Fleet $fleet
     * @return bool
     */
    public function addToFleet(Fleet $fleet):bool{
        //check if the vehicle is part of the fleet
        if(in_array($fleet, $this->lstFleets)){
            //if it is already, return false
            return false;
        }
        //the vehicle is not part of the fleet, we add it
        $this->lstFleets[] = $fleet;
        return true;
    }

    /**
     * Removes the vehicle from a fleet if it is part of it
     * Returns true if the vehicle was removed from the fleet, returns false if the vehicle was not part of the given fleet
     * @param Fleet $fleet
     * @return bool
     */
    public function removeFromFleet(Fleet $fleet):bool{
        //check if the vehicle is part of $fleet
        $key = array_search($fleet, $this->lstFleets, true);
        if($key === false){
            //if it is not, return false
            return false;
        }
        //the vehicle is part of the fleet, we remove it
        unset($this->lstFleets[$key]);
        return true;
    }

    public function getLocation():Location
    {
        return $this->location;
    }

    /**
     * Parks a vehicle to a location if the vehicle is not already there
     * Returns true if the location has been set, returns false if the vehicle was already at this location
     * @param Location $location
     * @return bool
     */
    public function setLocation(Location $location):bool
    {
        if($this->location->getId() === $location->getId()){
            return false;
        }
        $this->location = $location;
        return true;
    }
}