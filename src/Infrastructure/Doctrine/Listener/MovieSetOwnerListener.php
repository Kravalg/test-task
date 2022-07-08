<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Listener;

use App\Domain\Entity\Movie;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class MovieSetOwnerListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getDocument();

        if (!($entity instanceof Movie)) {
            return;
        }

        if (!empty($entity->getOwner())) {
            return;
        }

        $user = $this->security->getUser();
        if ($user) {
            $entity->setOwner($user);
        }
    }
}
