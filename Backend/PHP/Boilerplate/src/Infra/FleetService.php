<?php
namespace App\Infra;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Exception\ORMException;
use App\Domain\Fleet;

class FleetService
{
    private ManagerRegistry  $doctrine;
    public function __construct(ManagerRegistry  $doctrine){
        $this->doctrine = $doctrine;
    }

    /**
     * Persists an instance of Fleet in the DB
     * @param Fleet $fleet
     * @return string
     * @throws ORMException
     */
    public function saveFleet(Fleet $fleet):string{
        //$this->doctrine->->persist($fleet);
        return $fleet->getId();
    }
}