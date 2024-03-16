<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\App\Calculator;
use Fulll\Domain\Fleet;
use Fulll\Domain\Vehicle;
use Fulll\App\FleetManager;

class FeatureContext implements Context
{
    /**
     * Use $this->fleetManager when necessary instead of create a new instance in every function that needs it
     * @var FleetManager $fleetManager
     */
    private FleetManager $fleetManager;
    public function __construct(){
        $this->fleetManager = new FleetManager();
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
        if(!$this->fleetManager->associateFleetAndVehicle($this->aVehicle, $this->myFleet)){
            throw new RuntimeException(sprintf('The vehicle with id %s is already part of the fleet with id %s (fleet owner : %s)',
                $this->aVehicle->getId(), $this->myFleet->getId(), $this->myFleet->getUsername()));
        }
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function registerVehicleToSomeoneElseFleet():void
    {
        if(!$this->fleetManager->associateFleetAndVehicle($this->aVehicle, $this->someoneElseFleet)){
            throw new RuntimeException(sprintf('The vehicle with id %s is already part of the fleet with id %s (fleet owner : %s)',
                $this->aVehicle->getId(), $this->someoneElseFleet->getId(), $this->someoneElseFleet->getUsername()));
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     * @Then I should be informed that this vehicle has already been registered into my fleet
     */
    public function checkIfAVehicleBelongsToMyFleet():void{
        if(!$this->fleetManager->checkIfVehicleIsPartOfFleet($this->aVehicle, $this->myFleet)){
            throw new \RuntimeException(sprintf('The vehicle with id %s should be part of the fleet with id %s
            (fleet owner : %s), but is not.',
                $this->aVehicle->getId(),
                $this->myFleet->getId(),
                $this->myFleet->getUsername()
            ));
        }
    }
}
