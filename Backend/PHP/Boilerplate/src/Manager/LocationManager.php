<?php
namespace App\Manager;

use App\Entity\Location;
use App\Infra\LocationService;
use Doctrine\ORM\Exception\ORMException;

class LocationManager{

    public function __construct(private LocationService $locationService){}

    /**
     * Persists a vehicle into the DB
     * @param Location $location
     * @return string
     * @throws ORMException
     */
    public function saveOneLocation(Location $location):string{
        return $this->locationService->saveOneLocation($location);
    }

    /**
     * Checks the DB for a location that situated at this longitude and latitude
     * @param float $longitude
     * @param float $latitude
     * @return Location
     * @throws \InvalidArgumentException
     */
    public function getOneByLongitudeAndLatitude(float $longitude, float $latitude):Location{
        return $this->locationService->getOneByLongitudeAndLatitude($longitude, $latitude);
    }
}