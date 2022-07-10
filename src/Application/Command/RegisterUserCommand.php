<?php

namespace App\Application\Command;

use App\Domain\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RegisterUserCommand extends Command
{
    protected static $defaultName = 'app:register-user';
    protected static $defaultDescription = 'Allows to create user';

    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        parent::__construct();

        $this->userService = $userService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = (string) $input->getArgument('email');
        $password = (string) $input->getArgument('password');

        $this->userService->createOrUpdate($email, $password);

        $io->success('User registered or updated!');

        return Command::SUCCESS;
    }
}
