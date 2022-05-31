<?php

namespace App\Command;

use App\Services\CreateSuperAdminUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class CreateSuperAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-super-admin-user';
    protected static $description = 'Creates a new user with superAdmin role.';
    protected static $help = 'This command allows you to create a user with the role of superAdmin.';

  private $CreateSuperAdminUser;

  public function __construct(CreateSuperAdminUser $CreateSuperAdminUser)
  {
    $this->CreateSuperAdminUser = $CreateSuperAdminUser;

    parent::__construct();
  }
    protected function configure(): void
    {
        $this
            ->setDescription(self::$description)
            ->setHelp(self::$help)
            ->addArgument('firstname', InputArgument::REQUIRED, 'The first name of the user.')
            ->addArgument('lastname', InputArgument::REQUIRED, 'The last name of the user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('password',  InputArgument::REQUIRED,'User password');
    }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $progressBar = new ProgressBar($output, 100);
    $progressBar->start();
    $progressBar->advance(1);
    $progressBar->finish();
    $io = new SymfonyStyle($input, $output);
    $firstName = $input->getArgument('firstname');
    $lastName = $input->getArgument('lastname');
    $email = $input->getArgument('email');
    $password = $input->getArgument('password');

    if ($firstName && $lastName && $email && $password) {
      $this->CreateSuperAdminUser->createUser($firstName,$lastName,$email,$password);
      $io->success('You have created a new user with superAdmin rights.');
    }

    return 0;
  }


}


