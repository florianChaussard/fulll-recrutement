<?php

namespace App\Command;

use App\Entity\Location;
use Doctrine\ORM\Exception\ORMException;
use App\Manager\LocationManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'location:create',
    hidden: false,
)]
class CreateLocationCommand extends Command
{
    /**
     * @var LocationManager $locationManager
     */
    private LocationManager $locationManager;
    /**
     * @var LoggerInterface $logger object used to write errors in var/log folder
     */
    private LoggerInterface $logger;

    public function __construct(LocationManager $locationManager, LoggerInterface $logger, ?string $name = null)
    {
        parent::__construct($name);
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
        $this->setHelp('This command allows you to create a location situated at the given longitude and latitude. You can add a name to it');
        $this->addArgument('longitude', InputArgument::REQUIRED, 'Location\'s longitude');
        $this->addArgument('latitude', InputArgument::REQUIRED, 'Location\'s latitude');
        $this->addArgument('name', InputArgument::OPTIONAL, 'Location\'s name (optional)');
        $this->setDescription('Creates a new location situated at given longitude and latitude. You can also add a name; displays location id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $longitude = $input->getArgument('longitude');
        $latitude = $input->getArgument('latitude');
        $name = $input->getArgument('name');

        $displayString = sprintf('Create a location situated at lng : %s; lat : %s', $longitude, $latitude);
        if($name){
            $displayString .=sprintf('; name : %s', $name);
        }

        $output->writeln([
            $displayString,
            '============',
        ]);

        if(!floatval($longitude) && $longitude !== '0'){
            throw new \InvalidArgumentException('The given longitude is not valid');
        }

        if(!floatval($latitude) && $latitude !== '0'){
            throw new \InvalidArgumentException('The given latitude is not valid');
        }


        $location = new Location();
        $location->setLongitude($longitude);
        $location->setLatitude($latitude);
        if($name){
            $location->setName($name);
        }

        try {
            $this->locationManager->saveOneLocation($location);
        }
        catch(ORMException $e){
            $this->logger->error(sprintf('Failed to create a location by command. Error message : %s', $e->getMessage()));
            $output->writeln("The creation of the location failed.");
            return Command::FAILURE;
        }
        $output->writeln([
            'Location created successfuly at longitude '.$location->getLongitude(). ' and latitude '.$location->getLatitude(),
            'New location id : '.$location->getId(),
        ]);

        return Command::SUCCESS;
    }
}