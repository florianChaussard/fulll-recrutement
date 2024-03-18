<?php

namespace App\App;

use App\Domain\Vehicle;
use App\Domain\Location;

class VehicleHelper
{
    /**
     * Checks whether a given Vehicle is parked at a given Location
     * @param Vehicle $vehicle
     * @param Location $location
     * @return bool
     */
   public function checkIfVehicleIsAtLocation(Vehicle $vehicle, Location $location):bool{
       return $vehicle->getLocation()->getId() === $location->getId();
   }
}