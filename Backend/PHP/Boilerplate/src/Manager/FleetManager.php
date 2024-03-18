<?php
namespace App\Manager;

use Doctrine\ORM\Exception\ORMException;
use App\Domain\Fleet;
use App\Infra\FleetService;

class FleetManager{

    public function __construct(private FleetService $fleetService){}

    /**
     * Persists a fleet into the DB
     * @param Fleet $fleet
     * @return string
     * @throws ORMException
     */
    public function saveFleet(Fleet $fleet):string{
        return $this->fleetService->saveFleet($fleet);
    }
}