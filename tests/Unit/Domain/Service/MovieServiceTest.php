<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service;

use App\Domain\Entity\Movie;
use App\Domain\Entity\User;
use App\Domain\Service\MovieService;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;

class MovieServiceTest extends TestCase
{
    public function testSetCurrentUserAsOwner(): void
    {
        $faker = Factory::create();
        $sender = $faker->companyEmail();
        $userEmail = $faker->email();

        $user = new User();
        $user->setEmail($userEmail);

        $security = $this->createMock(Security::class);
        $security->expects($this->once())
            ->method('getUser')
            ->will($this->returnValue($user));
        $mailer = $this->createMock(MailerInterface::class);

        $movieService = new MovieService($security, $mailer, $sender);
        $movie = new Movie();

        $movie = $movieService->setCurrentUserAsOwner($movie);

        $this->assertNotNull($movie->getOwner());
        $this->assertEquals($movie->getOwner()->getUserIdentifier(), $userEmail);
    }
}
