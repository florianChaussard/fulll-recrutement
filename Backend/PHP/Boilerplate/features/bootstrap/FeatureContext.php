<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use App\App\Calculator;
use App\Domain\Fleet;
use App\Domain\Vehicle;
use App\Domain\Location;
use App\App\FleetHelper;
use App\App\VehicleHelper;

class FeatureContext implements Context
{
    /**
     * Use $this->fleetHelper when necessary instead of creating a new instance in every function that needs it
     * @var FleetHelper $fleetHelper
     */
    private FleetHelper $fleetHelper;

    /**
     * Use $this->vehicleManager when necessary instead of creating a new instance in every function that needs it
     * @var VehicleHelper $vehicleManager
     */
    private VehicleHelper $vehicleManager;
    public function __construct(){
        $this->fleetHelper = new FleetHelper();
        $this->vehicleManager = new VehicleHelper();
    }

    /**
     * ===================
     * behat.feature
     * ===================
     */
    private bool $errorVehicleAlreadyAdded = false;
    /**
     * @When I multiply :a by :b into :var
     */
    public function iMultiply(int $a, int $b, string $var): void
    {
        $calculator = new Calculator();
        $this->$var = $calculator->multiply($a, $b);
    }

    /**
     * @Then :var should be equal to :value
     */
    public function aShouldBeEqualTo(string $var, int $value): void
    {
        if ($value !== $this->$var) {
            throw new \RuntimeException(sprintf('%s is expected to be equal to %s, got %s', $var, $value, $this->$var));
        }
    }


    /**
     * ===================
     * register_vehicule.feature
     * ===================
     */

    /**
     * @var Fleet $myFleet the fleet that belongs to me
     */
    private Fleet $myFleet;

    /**
     * @var Fleet $someoneElseFleet the fleet that belongs to someone else
     */
    private Fleet $someoneElseFleet;

    /**
     * @var Vehicle $aVehicle a vehicle
     */
    private Vehicle $aVehicle;

    /**
     * @Given my fleet
     */
    public function createMyFleet():Fleet
    {
        $this->myFleet = new Fleet('Florian');
        return $this->myFleet;
    }

    /**
     * @Given the fleet of another user
     */
    public function createSomeoneElseFleet():Fleet
    {
        $this->someoneElseFleet = new Fleet('Someone Else');
        return $this->someoneElseFleet;
    }

    /**
     * @Given a vehicle
     */
    public function createNewVehicle():Vehicle
    {
        $this->aVehicle = new Vehicle();
        return $this->aVehicle;
    }

    /**
     * @Given I have registered this vehicle into my fleet
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     */
    public function registerAVehicleToMyFleet():void
    {
        if(!$this->fleetHelper->associateFleetAndVehicle($this->aVehicle, $this->myFleet)){
            throw new RuntimeException(sprintf('The vehicle with id %s is already part of the fleet with id %s (fleet owner : %s)',
                $this->aVehicle->getId(), $this->myFleet->getId(), $this->myFleet->getUsername()));
        }
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function registerVehicleToSomeoneElseFleet():void
    {
        if(!$this->fleetHelper->associateFleetAndVehicle($this->aVehicle, $this->someoneElseFleet)){
            throw new RuntimeException(sprintf('The vehicle with id %s is already part of the fleet with id %s (fleet owner : %s)',
                $this->aVehicle->getId(), $this->someoneElseFleet->getId(), $this->someoneElseFleet->getUsername()));
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     * @Then I should be informed that this vehicle has already been registered into my fleet
     */
    public function checkIfAVehicleBelongsToMyFleet():void{
        if(!$this->fleetHelper->checkIfVehicleIsPartOfFleet($this->aVehicle, $this->myFleet)){
            throw new \RuntimeException(sprintf('The vehicle with id %s should be part of the fleet with id %s
            (fleet owner : %s), but is not.',
                $this->aVehicle->getId(),
                $this->myFleet->getId(),
                $this->myFleet->getUsername()
            ));
        }
    }



    /**
     * ===================
     * park_vehicle.feature
     * ===================
     */

    /**
     * @var Location $aLocation a place to park a vehicle
     */
    private Location $aLocation;

    /**
     * @Given a location
     */
    public function createNewLocation():Location{
        $this->aLocation = new Location();
        return $this->aLocation;
    }

    /**
     * @Given my vehicle has been parked into this location
     * @When I park my vehicle at this location
     * @When I try to park my vehicle at this location
     */
    public function parkVehicleToLocation():void
    {
        if(!$this->aVehicle->setLocation($this->aLocation)){
            throw new RuntimeException(sprintf('The vehicle with id %s is already parked at location with id %s (location name : %s)',
                $this->aVehicle->getId(),
                $this->aLocation->getId(), $this->aLocation->getName()));
        }
    }

    /**
     * @Then the known location of my vehicle should verify this location
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function checkIfVehicleIsAtLocation():void
    {
        if(!$this->vehicleManager->checkIfVehicleIsAtLocation($this->aVehicle, $this->aLocation)){
            throw new \RuntimeException(sprintf('The vehicle with id %s should be at the location with id %s
            (location name : %s), but is not.',
                $this->aVehicle->getId(),
                $this->aLocation->getId(),
                $this->aLocation->getName()
            ));
        }
    }


}
