<?php

namespace Fulll\Infra;

Class Fleet{
    /** @var string an unique identifier for the fleet */
    private string $id;
    /** @var User Whose does this fleet belong to */
    private User $user;
    /** @var array The list of vehicles that are part of the fleet */
    private array $lstVehicles;

    public function __construct(User $user)
    {
        $this->id = uniqid();
        $this->user = $user;
        $this->lstVehicles = [];
    }

    public function getId():string
    {
        return $this->id;
    }

    public function getUser():User
    {
        return $this->user;
    }

    public function setUser(User $user):self{
        $this->user = $user;
        return $this;
    }

    public function getLstVehicles():array
    {
        return $this->getLstVehicles();
    }

    public function addVehicleToFleet(Vehicle $vehicle, bool $alsoAddInVehicle = true):bool{
        if(!in_array($vehicle, $this->lstVehicles)){
            $this->lstVehicles[] = $vehicle;
            if($alsoAddInVehicle){
                //avoid loop where addToFleet calls addVehicleToFleet and vice versa
                $vehicle->addToFleet($this, false);
            }
        }
        return false;
    }

    public function removeVehicleFromFleet(Vehicle $vehicle):self{
        $key = array_search($vehicle, $this->lstVehicles, true);
        if($key !== false){
            unset($this->lstVehicles[$key]);
            $vehicle->removeFromFleet($this);
        }
        return $this;
    }

    public function checkIfVehicleIsPartOfFleet(Vehicle $vehicle):bool{
        return in_array($vehicle, $this->lstVehicles);
    }
}