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
        $email = (string) $input->getArgument('email');
        $password = (string) $input->getArgument('password');

        $userRepository = $this->objectManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]) ?? new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $password)
        );

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $io->success('User registered or updated!');

        return Command::SUCCESS;
    }
}
