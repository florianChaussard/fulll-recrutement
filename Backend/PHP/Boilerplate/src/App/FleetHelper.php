<?php

namespace App\App;

use App\Domain\Fleet;
use App\Domain\Vehicle;

class FleetHelper
{
    /**
     * Adds a vehicle to a fleet and vice versa
     * Returns true if the vehicle was not part of the fleet already, false otherwise
     * @param Vehicle $vehicle
     * @param Fleet $fleet
     * @return bool
     */
    public function associateFleetAndVehicle(Vehicle $vehicle, Fleet $fleet):bool{
        return $fleet->addVehicleToFleet($vehicle) && $vehicle->addToFleet($fleet);
    }

    /**
     * Checks if a vehicle is part of a fleet
     * Return true if it is, false otherwise
     * @param Vehicle $vehicle
     * @param Fleet $fleet
     * @return bool
     */
    public function checkIfVehicleIsPartOfFleet(Vehicle $vehicle, Fleet $fleet):bool{
        $fleetVehicles = $fleet->getLstVehicles();
        return in_array($vehicle, $fleetVehicles);
    }
}