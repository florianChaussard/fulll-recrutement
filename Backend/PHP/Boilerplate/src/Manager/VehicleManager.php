<?php
namespace App\Manager;

use App\Entity\Vehicle;
use App\Infra\VehicleService;
use Doctrine\ORM\Exception\ORMException;

class VehicleManager{

    public function __construct(private VehicleService $vehicleService){}

    /**
     * Persists a vehicle into the DB
     * @param Vehicle $vehicle
     * @return string
     * @throws ORMException
     */
    public function saveOneVehicle(Vehicle $vehicle):string{
        return $this->vehicleService->saveOneVehicle($vehicle);
    }

    /**
     * Checks the DB for a vehicle that has this plate number
     * @param string $plateNumber
     * @return Vehicle
     * @throws \InvalidArgumentException
     */
    public function getOneByPlateNumber(string $plateNumber):Vehicle{
        return $this->vehicleService->getOneByPlateNumber($plateNumber);
    }
}