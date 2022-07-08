<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"movie", "ratings"}},
 *     denormalizationContext={"groups"={"movie", "ratings"}},
 *     itemOperations={
 *       "get",
 *       "put"= {"security_post_denormalize"= "object.owner == user"},
 *       "patch"= {"security_post_denormalize"= "object.owner == user"},
 *     },
 * )
 * @ODM\Document
 */
class Movie
{
    /**
     * @ODM\Id(strategy="INCREMENT", type="int")
     * @Groups("movie")
     */
    private int $id;

    /**
     * @ODM\Field(type="string")
     * @Assert\NotBlank
     * @Groups("movie")
     */
    private string $name;

    /**
     * @ODM\Field(type="string")
     * @Assert\NotBlank
     * @Groups("movie")
     */
    private string $director;

    /**
     * @ODM\Field(type="date")
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     * @Groups("movie")
     */
    private DateTimeInterface $releaseDate;

    /**
     * @ODM\Field(type="collection")
     * @Assert\NotBlank
     * @Groups("movie")
     */
    private array $casts = [];

    /**
     * @var Collection<int, Rating>
     * @ODM\EmbedMany(targetDocument=Rating::class)
     * @Assert\NotBlank
     * @Assert\Valid()
     * @Groups("movie", "ratings")
     */
    private Collection $ratings;

    /**
     * @ODM\ReferenceOne(targetDocument=User::class, storeAs="id")
     */
    private ?UserInterface $owner = null;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDirector(): string
    {
        return $this->director;
    }

    public function setDirector(string $director): void
    {
        $this->director = $director;
    }

    public function getReleaseDate(): DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTimeInterface $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function getCasts(): array
    {
        return $this->casts;
    }

    public function setCasts(array $casts): void
    {
        $this->casts = $casts;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * @param array|Collection<int, Rating> $ratings
     */
    public function setRatings($ratings): void
    {
        $this->ratings = is_array($ratings) ? new ArrayCollection($ratings) : $ratings;
    }

    public function getOwner(): ?UserInterface
    {
        return $this->owner;
    }

    public function setOwner(UserInterface $owner): void
    {
        $this->owner = $owner;
    }
}
