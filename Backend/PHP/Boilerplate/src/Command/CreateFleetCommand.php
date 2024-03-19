<?php

namespace App\Command;

use Doctrine\ORM\Exception\ORMException;
use App\Entity\Fleet;
use App\Manager\FleetManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'fleet:create',
    hidden: false,
)]
class CreateFleetCommand extends Command
{
    /**
     * @var FleetManager $fleetManager
     */
    private FleetManager $fleetManager;
    /**
     * @var LoggerInterface $logger object used to write errors in var/log folder
     */
    private LoggerInterface $logger;

    public function __construct(FleetManager $fleetManager, LoggerInterface $logger, ?string $name = null)
    {
        parent::__construct($name);
        $this->fleetManager = $fleetManager;
        $this->logger = $logger;
    }

    /**
     * The command configuration
     * @return void
     */
    protected function configure(): void
    {
        //The text shown when running the command with --help flag
        $this->setHelp('This command allows you to create a fleet for the given user.');
        $this->addArgument('username', InputArgument::REQUIRED, 'Fleet owner\'s username');
        $this->setDescription('Creates a new fleet for a user; displays fleet id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');

        $output->writeln([
            'Create a fleet for user '.$username,
            '============',
        ]);

        $fleet = new Fleet();
        $fleet->setUsername($username);
        try {
            $this->fleetManager->saveOneFleet($fleet);
        }
        catch(ORMException $e){
            $this->logger->error(sprintf('Failed to create a fleet by command. Error message : %s', $e->getMessage()));
            $output->writeln("The creation of the fleet failed.");
            return Command::FAILURE;
        }
        $output->writeln([
            'Fleet created successfuly for user '.$username,
            'New fleet id : '.$fleet->getId(),
        ]);

        return Command::SUCCESS;
    }
}