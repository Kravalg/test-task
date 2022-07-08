<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Domain\Entity\Movie;
use App\Domain\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class NewMovieSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    private string $sender;

    public function __construct(MailerInterface $mailer, string $sender)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $movie = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$movie instanceof Movie || Request::METHOD_POST !== $method) {
            return;
        }

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
