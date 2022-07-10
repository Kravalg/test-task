<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Listener;

use App\Domain\Entity\Movie;
use App\Domain\Service\MovieService;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class MovieSetOwnerListener
{
    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getDocument();

        if (!($entity instanceof Movie)) {
            return;
        }

        $this->movieService->setCurrentUserAsOwner($entity);
    }
}
