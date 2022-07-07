<?php

namespace App\Application\Command;

use App\Domain\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterUserCommand extends Command
{
    protected static $defaultName = 'app:register-user';
    protected static $defaultDescription = 'Allows to create user';

    private ObjectManager $objectManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ObjectManager $objectManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();

        $this->objectManager = $objectManager;
        $this->passwordHasher = $passwordHasher;
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
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $io->success('User registered!');

        return Command::SUCCESS;
    }
}
