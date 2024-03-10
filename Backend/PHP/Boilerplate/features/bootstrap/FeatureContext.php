<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\App\Calculator;
use Fulll\Infra\Fleet;
use Fulll\Infra\User;
use Fulll\Infra\Vehicle;

class FeatureContext implements Context
{
    private User $myUser;
    private User $someoneElse;
    private Fleet $myFleet;
    private Fleet $someoneElseFleet;
    private Vehicle $aVehicle;

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
     * @Given my fleet
     */
    public function getMyFleet():Fleet
    {
        if(isset($this->myFleet)){
            return $this->myFleet;
        }
        if(!isset($this->myUser)){
            $this->myUser = new User('me');
        }
        $this->myFleet = new Fleet($this->myUser);
        return $this->myFleet;
    }

    /**
     * @Given the fleet of another user
     * @return Fleet
     */
    public function getAnotherUsersFleet():Fleet
    {
        if(isset($this->someoneElseFleet)){
            return $this->someoneElseFleet;
        }
        if(!isset($this->someoneElse)){
            $this->someoneElse = new User('Someone Else');
        }
        $this->someoneElseFleet = new Fleet($this->someoneElse);
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
    public function registerVehicleToMyFleet():bool
    {
        if(!$this->myFleet->addVehicleToFleet($this->aVehicle)){
            $this->errorVehicleAlreadyAdded = true;
            return false;
        }
        return true;
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     * @return bool
     */
    public function registerVehicleToSomeoneElseFleet():bool
    {
        if(!$this->someoneElseFleet->addVehicleToFleet($this->aVehicle)){
            $this->errorVehicleAlreadyAdded = true;
            return false;
        }
        return true;
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function checkIfVehicleBelongsToMyFleet():void{
        if(!$this->myFleet->checkIfVehicleIsPartOfFleet($this->aVehicle)){
            throw new \RuntimeException(sprintf('The vehicle %s should be part of the fleet that belongs to %s, but is not.', $this->aVehicle->getType(), $this->myFleet->getUser()->getName()));
        }
    }

    /**
     * @Then I should be informed that this vehicle has already been registered into my fleet
     */
    public function checkIfErrorVehicleAlreadyAddedToFleetIsTrue()
    {
        if(!$this->errorVehicleAlreadyAdded){
            throw new \RuntimeException('I tried to add the same vehicle to a fleet twice and no error was thrown');
        }
    }
}
