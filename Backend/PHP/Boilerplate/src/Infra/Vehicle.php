<?php

namespace Fulll\Infra;
Class Vehicle{
    /** @var string an unique identifier for the vehicle */
    private string $id;
    /** @var string type the vehicle's type (car, moto, plane, donkey...)*/
    private string $type;
    /** @var array The list of fleets that the vehicle is in */
    private array $lstFleets;

    public function __construct(string $type = '')
    {
        $this->id = uniqid();
        $this->type = $type;
        $this->lstFleets = [];
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

    public function addToFleet(Fleet $fleet, bool $alsoAddInFleet = true):bool{
        if(!in_array($fleet, $this->lstFleets)){
            $this->lstFleets[] = $fleet;
            if($alsoAddInFleet){
                //avoid loop where addVehicleToFleet calls addToFleet and vice versa
                $fleet->addVehicleToFleet($this, false);
            }
            return true;
        }
        return false;
    }

    public function removeFromFleet(Fleet $fleet):self{
        $key = array_search($fleet, $this->lstFleets, true);
        if($key !== false){
            unset($this->lstFleets[$key]);
            $fleet->removeVehicleFromFleet($this);
        }
        return $this;
    }

    public function checkIfVehicleIsPartOfFleet(Fleet $fleet):bool
    {
        return in_aray($fleet, $this->lstFleets);
    }
}