<?php

namespace App\Command;

use App\Manager\VehicleManager;
use App\Manager\FleetManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'fleet:localize:vehicle',
    hidden: false,
)]
class LocalizeVehicleCommand extends Command
{
    /**
     * @var FleetManager $fleetManager
     */
    private FleetManager $fleetManager;
    /**
     * @var VehicleManager $vehicleManager
     */
    private VehicleManager $vehicleManager;

    public function __construct(FleetManager $fleetManager, VehicleManager $vehicleManager, ?string $name = null)
    {
        parent::__construct($name);
        $this->fleetManager = $fleetManager;
        $this->vehicleManager = $vehicleManager;
    }

    /**
     * The command configuration
     * @return void
     */
    protected function configure(): void
    {
        //The text shown when running the command with --help flag
        $this->setHelp('This command allows you to localize a vehicle within a fleet. You need to give the fleet id and the vehicle\'s plate number');
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet\'s identifiant value');
        $this->addArgument('plateNumber', InputArgument::REQUIRED, 'Vehicle\'s plate number');
        $this->setDescription('Localizes a vehicle within a fleet. Returns the longitude and latitude of given vehicle');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fleetId = $input->getArgument('fleetId');
        $plateNumber = $input->getArgument('plateNumber');

        $output->writeln([
            'Localize vehicle with plate number '.$plateNumber.' within fleet '.$fleetId,
            '============',
        ]);

        if(!intval($fleetId) && $fleetId !== '0'){
            throw new \InvalidArgumentException('The given fleet ID is not an integer');
        }

        try{
            $fleet = $this->fleetManager->getOneById(intval($fleetId));
            $vehicle = $this->vehicleManager->getOneByPlateNumber($plateNumber);
        }
        catch(\InvalidArgumentException $e){
            $output->writeln("An error occured : ".$e->getMessage());
            return Command::FAILURE;
        }

        $lstVehiclesInFleet = $fleet->getLstVehicles();
        if (!($lstVehiclesInFleet->contains($vehicle))) {
            $output->writeln(sprintf("The vehicle with plate number %s is not part of %s's fleet", $vehicle->getPlateNumber(), $fleet->getUsername()));
            return Command::FAILURE;
        }

        if($vehicle->getLocation() === null){
            $output->writeln(sprintf("The vehicle with plate number %s has no known location", $vehicle->getPlateNumber()));
            return Command::FAILURE;
        }

        $output->writeln(sprintf('Localization successful : vehicle with plate number %s is part of %s\'s fleet and is situated at longitude %s and latitude %s',
        $vehicle->getPlateNumber(), $fleet->getUsername(), $vehicle->getLocation()->getLongitude(), $vehicle->getLocation()->getLatitude()));

        return Command::SUCCESS;
    }
}