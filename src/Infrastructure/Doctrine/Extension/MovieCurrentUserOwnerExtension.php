<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\MongoDbOdm\Extension\AggregationCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\MongoDbOdm\Extension\AggregationItemExtensionInterface;
use App\Domain\Entity\Movie;
use App\Domain\Entity\User;
use Doctrine\ODM\MongoDB\Aggregation\Builder;
use Symfony\Component\Security\Core\Security;

final class MovieCurrentUserOwnerExtension implements AggregationCollectionExtensionInterface, AggregationItemExtensionInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        Builder $aggregationBuilder,
        string $resourceClass,
        string $operationName = null,
        array &$context = []
    ): void {
        $this->addOwnerFilter($aggregationBuilder, $resourceClass);
    }

    public function applyToItem(Builder $aggregationBuilder, string $resourceClass, array $identifiers, string $operationName = null, array &$context = []): void
    {
        $this->addOwnerFilter($aggregationBuilder, $resourceClass);
    }

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     */
    private function addOwnerFilter(Builder $aggregationBuilder, string $resourceClass): void
    {
        /** @var User $user */
        if (Movie::class !== $resourceClass || null === $user = $this->security->getUser()) {
            return;
        }

        $aggregationBuilder
            ->match()
            ->field('owner')->equals($user->getId());
    }
}
