<?php
namespace App\Infra;

use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use App\Entity\Vehicle;

class VehicleService
{
    public function __construct(private EntityManagerInterface  $em, private VehicleRepository $vehicleRepository){}

    /**
     * Persists an instance of Vehicle in the DB
     * @param Vehicle $vehicle
     * @return string
     * @throws ORMException
     */
    public function saveOneVehicle(Vehicle $vehicle):string{
        $this->em->persist($vehicle);
        $this->em->flush();
        return $vehicle->getId();
    }

    /**
     * Checks the DB for a vehicle that has this plate number
     * @param string $plateNumber
     * @return Vehicle
     * @throws \InvalidArgumentException
     */
    public function getOneByPlateNumber(string $plateNumber):Vehicle
    {
        $vehicle = $this->vehicleRepository->findOneBy(['plateNumber'=>$plateNumber]);
        if(is_null($vehicle)){
            throw new \InvalidArgumentException('No vehicle found for plate number '.$plateNumber);
        }
        return $vehicle;
    }
}