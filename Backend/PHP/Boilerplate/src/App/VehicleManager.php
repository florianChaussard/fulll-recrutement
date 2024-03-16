<?php

namespace Fulll\App;

use Fulll\Domain\Vehicle;
use Fulll\Domain\Location;

class VehicleManager
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