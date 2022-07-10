<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Service\UserService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserServiceTest extends TestCase
{
    public function testCreateOrUpdate(): void
    {
        $faker = Factory::create();
        $email = $faker->email();
        $plainPassword = $faker->password();

        $userRepository = $this->createMock(ObjectRepository::class);
        $userRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(null));
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($userRepository));
        $passwordHasher = $this->createMock(UserPasswordHasher::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->will($this->returnValue('hash'));

        $userService = new UserService($objectManager, $passwordHasher);

        $user = $userService->createOrUpdate($email, $plainPassword);

        $this->assertNotNull($user);
        $this->assertEquals($email, $user->getEmail());
    }
}
