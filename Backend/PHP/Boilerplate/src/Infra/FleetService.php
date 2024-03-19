<?php
namespace App\Infra;

use App\Repository\FleetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use App\Entity\Fleet;

class FleetService
{
    public function __construct(private EntityManagerInterface  $em, private FleetRepository $fleetRepository){}

    /**
     * Persists an instance of Fleet in the DB
     * @param Fleet $fleet
     * @return string
     * @throws ORMException
     */
    public function saveOneFleet(Fleet $fleet):string{
        $this->em->persist($fleet);
        $this->em->flush();
        return $fleet->getId();
    }

    /**
     * Checks the DB for a fleet that has this id
     * @param int $fleetId
     * @return Fleet
     * @throws \InvalidArgumentException
     */
    public function getOneById(int $fleetId):Fleet
    {
        $fleet = $this->fleetRepository->findOneBy(['id'=>$fleetId]);
        if(is_null($fleet)){
            throw new \InvalidArgumentException('No fleet found for id '.$fleetId);
        }
        return $fleet;
    }
}