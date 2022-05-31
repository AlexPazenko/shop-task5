<?php

namespace App\Command;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixturesLoadCommand extends Command
{
    protected static $defaultName = 'app:fixtures-load';
    protected static $description = 'Load fixtures';
    protected static $help = 'This command accepts an optional argument - the name of the entity. If you specify the name of the entity, fixtures will be created for that entity. If you do not specify an entity, fixtures will be created for all entities.';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$description)
            ->setHelp(self::$help)
            ->addArgument('entity', InputArgument::OPTIONAL, 'You can specify to which entity you can add fixture. This value is optional.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();
        $progressBar->advance(1);
        $progressBar->finish();
        $io = new SymfonyStyle($input, $output);
        $entity =  $input->getArgument('entity');
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        if ($entity) {
            $arguments = [
              '--append' => '--append',
              '--group' => [$entity],
            ];

          } else {
            $arguments = [
              '--append'  => '--append',
            ];
          }

        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);

        $io->success('Success!');

        return 0;
    }
}
