<?php

namespace Fulll\Domain;

Class Fleet{
    /** @var string an unique identifier for the fleet */
    private string $id;
    /** @var string Whose does this fleet belong to */
    private string $username;
    /** @var array The list of vehicles that are part of the fleet */
    private array $lstVehicles;

    public function __construct(string $username)
    {
        $this->id = uniqid();
        $this->username = $username;
        $this->lstVehicles = [];
    }

    public function getId():string
    {
        return $this->id;
    }

    public function getUsername():string
    {
        return $this->username;
    }

    public function setUsername(string $username):self{
        $this->username = $username;
        return $this;
    }

    public function getLstVehicles():array
    {
        return $this->lstVehicles;
    }

    /**
     * Adds a vehicle to the fleet if it is not already part of it
     * return true is the vehicle was added, false if the vehicle was already part of the fleet
     * @param Vehicle $vehicle
     * @return bool
     */
    public function addVehicleToFleet(Vehicle $vehicle):bool{
        //check if the vehicle is not already part of the fleet
        if(in_array($vehicle, $this->lstVehicles)){
            //if it is, return false
            return false;
        }
        //the vehicle is not part of the fleet yet, we add it
        $this->lstVehicles[] = $vehicle;
        return true;
    }

    /**
     * Removes a vehicle from the fleet if it is part of it
     * Returns true if the vehicle was removed, return false if the vehicle was not part of the fleet in the first place
     * @param Vehicle $vehicle
     * @return bool
     */
    public function removeVehicleFromFleet(Vehicle $vehicle):bool{
        //check if the vehicle really is part of the fleet
        $key = array_search($vehicle, $this->lstVehicles, true);
        if($key === false){
            //if not, return false
            return false;
        }
        //the vehicle is part of the fleet, we remove it
        unset($this->lstVehicles[$key]);
        return true;
    }
}