<?php

namespace App\Command;

use Doctrine\ORM\Exception\ORMException;
use App\Entity\Vehicle;
use App\Manager\VehicleManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'vehicle:create',
    hidden: false,
)]
class CreateVehicleCommand extends Command
{
    /**
     * @var VehicleManager $vehicleManager
     */
    private VehicleManager $vehicleManager;
    /**
     * @var LoggerInterface $logger object used to write errors in var/log folder
     */
    private LoggerInterface $logger;

    public function __construct(VehicleManager $vehicleManager, LoggerInterface $logger, ?string $name = null)
    {
        parent::__construct($name);
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
        $this->setHelp('This command allows you to create a vehicle with the given plate number.');
        $this->addArgument('plateNumber', InputArgument::REQUIRED, 'Vehicle\'s plate number');
        $this->setDescription('Creates a new vehicle with given plate number; displays vehicle id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plateNumber = $input->getArgument('plateNumber');

        $output->writeln([
            'Create a vehicle with plate number '.$plateNumber,
            '============',
        ]);

        $vehicle = new Vehicle();
        $vehicle->setPlateNumber($plateNumber);
        try {
            $this->vehicleManager->saveOneVehicle($vehicle);
        }
        catch(ORMException $e){
            $this->logger->error(sprintf('Failed to create a vehicle by command. Error message : %s', $e->getMessage()));
            $output->writeln("The creation of the vehicle failed.");
            return Command::FAILURE;
        }
        $output->writeln([
            'Vehicle created successfuly with plate number '.$plateNumber,
            'New vehicle id : '.$vehicle->getId(),
        ]);

        return Command::SUCCESS;
    }
}