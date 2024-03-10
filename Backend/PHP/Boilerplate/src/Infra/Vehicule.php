<?php

namespace Fulll\Infra;
Class Vehicule{
    /** @var string an unique identifier for the vehicule */
    private string $id;
    /** @var string type the vehicule's type (car, moto, plane, donkey...)*/
    private string $type;
    /** @var array The list of fleets that the vehicule is in */
    private array $lstFleets;

    public function __construct(string $type)
    {
        $this->id = uniqid();
        $this->type = $type;
        $this->lstFleets = [];
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

    public function getLstVehicules():array
    {
        return $this->getLstVehicules();
    }

    public function addVehiculeToFleet(Vehicule $vehicule):self{
        if(!in_array($vehicule, $this->lstVehicules)){
            $this->lstVehicules[] = $vehicule;
        }
        return $this;
    }

    public function removeVehiculeFromFleet(Vehicule $vehicule):self{
        $key = array_search($vehicule, $this->lstVehicules, true);
        if($key !== false){
            unset($this->lstVehicules[$key]);
        }
        return $this;
    }
}