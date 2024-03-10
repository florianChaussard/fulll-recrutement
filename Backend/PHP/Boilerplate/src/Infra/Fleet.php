<?php

namespace Fulll\Infra;
use Fulll\Infra\User;
Class Fleet{
    /** @var string an unique identifier for the fleet */
    private string $id;
    /** @var User Whose does this fleet belong to */
    private User $user;
    /** @var array The list of vehicules that are part of the fleet */
    private array $lstVehicules;

    public function __construct(User $user)
    {
        $this->id = uniqid();
        $this->user = $user;
        $this->lstVehicules = [];
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
            $vehicule->addtoFleet($this);
        }
        return $this;
    }

    public function removeVehiculeFromFleet(Vehicule $vehicule):self{
        $key = array_search($vehicule, $this->lstVehicules, true);
        if($key !== false){
            unset($this->lstVehicules[$key]);
            $vehicule->removeFromFleet($this);
        }
        return $this;
    }
}