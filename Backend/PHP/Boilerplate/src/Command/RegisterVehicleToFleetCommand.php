<?php

namespace App\Command;

use App\Manager\VehicleManager;
use Doctrine\ORM\Exception\ORMException;
use App\Manager\FleetManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'fleet:register:vehicle',
    hidden: false,
)]
class RegisterVehicleToFleetCommand extends Command
{
    /**
     * @var FleetManager $fleetManager
     */
    private FleetManager $fleetManager;
    /**
     * @var VehicleManager $vehicleManager
     */
    private VehicleManager $vehicleManager;
    /**
     * @var LoggerInterface $logger object used to write errors in var/log folder
     */
    private LoggerInterface $logger;

    public function __construct(FleetManager $fleetManager, VehicleManager $vehicleManager, LoggerInterface $logger, ?string $name = null)
    {
        parent::__construct($name);
        $this->fleetManager = $fleetManager;
        $this->vehicleManager = $vehicleManager;
        $this->logger = $logger;
    }

    /**
     * The command configuration
     * @return void
     */
    protected function configure(): void
    {
        //The text shown when running the command with --help flag
        $this->setHelp('This command allows you to register a vehicle to a fleet. You need to give the fleet id and the vehicle\'s plate number');
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'Fleet\'s identifiant value');
        $this->addArgument('plateNumber', InputArgument::REQUIRED, 'Vehicle\'s plate number');
        $this->setDescription('Registers a vehicle to a fleet');
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
            'Register vehicle with plate number '.$plateNumber.' to fleet '.$fleetId,
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
        if ($lstVehiclesInFleet->contains($vehicle)) {
            $output->writeln(sprintf("The vehicle with plate number %s is already part of %s's fleet", $vehicle->getPlateNumber(), $fleet->getUsername()));
            return Command::FAILURE;
        }

        $fleet->addToLstVehicle($vehicle);
        try {
            $this->fleetManager->saveOneFleet($fleet);
            $this->vehicleManager->saveOneVehicle($vehicle);
        }
        catch(ORMException $e){
            $this->logger->error(sprintf('Failed to register a vehicle to a fleet by command. Error message : %s', $e->getMessage()));
            $output->writeln("The registration failed, check the server logs for more informations.");
            return Command::FAILURE;
        }

        $output->writeln('Registration successful : vehicle with plate number '.$plateNumber.' is part of '.$fleet->getUsername().'\'s fleet',);

        return Command::SUCCESS;
    }
}