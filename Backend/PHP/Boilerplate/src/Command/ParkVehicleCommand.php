<?php

namespace App\Command;

use App\Manager\LocationManager;
use App\Manager\VehicleManager;
use Doctrine\ORM\Exception\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'vehicle:park',
    hidden: false,
)]
class ParkVehicleCommand extends Command
{
    /**
     * @var VehicleManager $vehicleManager
     */
    private VehicleManager $vehicleManager;
    /**
     * @var LocationManager $locationManager;
     */
    private LocationManager $locationManager;
    /**
     * @var LoggerInterface $logger object used to write errors in var/log folder
     */
    private LoggerInterface $logger;

    public function __construct(VehicleManager $vehicleManager, LocationManager $locationManager, LoggerInterface $logger, ?string $name = null)
    {
        parent::__construct($name);
        $this->vehicleManager = $vehicleManager;
        $this->locationManager = $locationManager;
        $this->logger = $logger;
    }

    /**
     * The command configuration
     * @return void
     */
    protected function configure(): void
    {
        //The text shown when running the command with --help flag
        $this->setHelp('This command allows you to park a vehicle to a given location. You need the vehicle\'s plate number and location longitude and latitude');
        $this->addArgument('plateNumber', InputArgument::REQUIRED, 'Vehicle\'s plate number');
        $this->addArgument('longitude', InputArgument::REQUIRED, 'Location\'s longitude');
        $this->addArgument('latitude', InputArgument::REQUIRED, 'Location\'s latitude');
        $this->setDescription('Parks a vehicle in a location');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plateNumber = $input->getArgument('plateNumber');
        $longitude = $input->getArgument('longitude');
        $latitude = $input->getArgument('latitude');

        $output->writeln([
            sprintf('Park vehicle with plate number %s to location with longitude %s and latitude %s',
                $plateNumber, $longitude, $latitude),
            '============'
        ]);

        if(!floatval($longitude) && $longitude !== '0'){
            throw new \InvalidArgumentException('The given longitude is not valid');
        }

        if(!floatval($latitude) && $latitude !== '0'){
            throw new \InvalidArgumentException('The given latitude is not valid');
        }

        try{
            $vehicle = $this->vehicleManager->getOneByPlateNumber($plateNumber);
            $location = $this->locationManager->getOneByLongitudeAndLatitude(floatval($longitude), floatval($latitude));
        }
        catch(\InvalidArgumentException $e){
            $output->writeln("An error occured : ".$e->getMessage());
            return Command::FAILURE;
        }

        if($vehicle->getLocation() && $vehicle->getLocation()->getId() == $location->getId()){
            $output->writeln(sprintf("The vehicle with plate number %s is already parked at this location", $plateNumber));
            return Command::FAILURE;
        }

        $location->addToLstVehicle($vehicle);
        try {
            $this->locationManager->saveOneLocation($location);
            $this->vehicleManager->saveOneVehicle($vehicle);
        }
        catch(ORMException $e){
            $this->logger->error(sprintf('Failed to park a vehicle to a location by command. Error message : %s', $e->getMessage()));
            $output->writeln("The parking failed, check the server logs for more informations.");
            return Command::FAILURE;
        }

        $output->writeln(sprintf('Parking successful : vehicle with plate number %s is now parked at location with longitude %s and latitude %s',
        $vehicle->getPlateNumber(), $location->getLongitude(), $location->getLatitude()));

        return Command::SUCCESS;
    }
}