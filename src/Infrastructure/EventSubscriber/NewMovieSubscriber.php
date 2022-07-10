<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Domain\Entity\Movie;
use App\Domain\Service\MovieService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class NewMovieSubscriber implements EventSubscriberInterface
{
    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$entity instanceof Movie || Request::METHOD_POST !== $method) {
            return;
        }

        $this->movieService->sendNewMovieEmail($entity);
    }
}
