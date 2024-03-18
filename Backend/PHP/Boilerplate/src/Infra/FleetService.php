<?php
namespace App\Infra;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use App\Entity\Fleet;

class FleetService
{
    public function __construct(private EntityManagerInterface  $em){}

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
}