<?php

declare(strict_types=1);

namespace Doctrine\Migrations\Tools\Console\Command;

use Doctrine\Migrations\Exception\NoMigrationsToExecute;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function sprintf;

/**
 * The LatestCommand class is responsible for outputting what your latest version is.
 */
class LatestCommand extends DoctrineCommand
{
    /** @var string */
    protected static $defaultName = 'migrations:latest';

    protected function configure() : void
    {
        $this
            ->setAliases(['latest'])
            ->setDescription('Outputs the latest version number');

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output) : ?int
    {
        $aliasResolver = $this->getDependencyFactory()->getVersionAliasResolver();

        try {
            $version            = $aliasResolver->resolveVersionAlias('latest');
            $availableMigration = $this->getDependencyFactory()->getMigrationRepository()->getMigration($version);
            $description        = $availableMigration->getMigration()->getDescription();
        } catch (NoMigrationsToExecute $e) {
            $version     = '0';
            $description = '';
        }

        $output->writeln(sprintf(
            '<info>%s</info>%s',
            $version,
            $description !== '' ? ' - ' . $description : ''
        ));

        return 0;
    }
}