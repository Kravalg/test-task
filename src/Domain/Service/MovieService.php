<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Movie;
use App\Domain\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;

class MovieService
{
    private Security $security;

    private MailerInterface $mailer;

    private string $sender;

    public function __construct(Security $security, MailerInterface $mailer, string $sender)
    {
        $this->security = $security;
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    public function setCurrentUserAsOwner(Movie $movie): Movie
    {
        if (!empty($movie->getOwner())) {
            return $movie;
        }

        $user = $this->security->getUser();
        if ($user) {
            $movie->setOwner($user);
        }

        return $movie;
    }

    public function sendNewMovieEmail(Movie $movie): void
    {
        /** @var User $user */
        $user = $movie->getOwner();

        $message = (new Email())
            ->from($this->sender)
            ->to($user->getEmail())
            ->subject('A new movie has been added')
            ->text(sprintf('The movie "%s" has been added.', $movie->getName()));

        $this->mailer->send($message);
    }
}
