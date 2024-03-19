<?php
namespace App\Infra;

use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use App\Entity\Location;

class LocationService
{
    public function __construct(private EntityManagerInterface  $em, private LocationRepository $locationRepository){}

    /**
     * Persists an instance of Location in the DB
     * @param Location $location
     * @return string
     * @throws ORMException
     */
    public function saveOneLocation(Location $location):string{
        $this->em->persist($location);
        $this->em->flush();
        return $location->getId();
    }

    /**
     * Checks the DB for a location that situated at this longitude and latitude
     * @param float $longitude
     * @param float $latitude
     * @return Location
     * @throws \InvalidArgumentException
     */
    public function getOneByLongitudeAndLatitude(float $longitude, float $latitude):Location
    {
        $location = $this->locationRepository->findOneBy(['longitude'=>$longitude, 'latitude'=>$latitude]);
        if(is_null($location)){
            throw new \InvalidArgumentException('No location found for longitude '.$longitude.' and latitude '.$latitude);
        }
        return $location;
    }
}