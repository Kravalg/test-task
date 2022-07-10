<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private ObjectManager $objectManager;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ObjectManager $objectManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->objectManager = $objectManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function createOrUpdate(string $email, string $plainPassword): User
    {
        $userRepository = $this->objectManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]) ?? new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        return $user;
    }
}
