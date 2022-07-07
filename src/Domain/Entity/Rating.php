<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"ratings", "movie"}},
 *     denormalizationContext={"groups"={"ratings", "movie"}}
 * )
 * @ODM\EmbeddedDocument
 */
class Rating
{
    /**
     * @ODM\Id(strategy="INCREMENT", type="int")
     * @Groups("ratings", "movie")
     */
    private int $id;

    /**
     * @ODM\Field(type="string")
     * @Assert\NotBlank
     * @Groups("ratings", "movie")
     */
    private string $name;

    /**
     * @ODM\Field(type="float")
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     * @Groups("ratings", "movie")
     */
    private float $value;

    /**
     * @ODM\ReferenceOne(targetDocument=Movie::class, inversedBy="ratings")
     * @Assert\Valid()
     */
    private Movie $movie;

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

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): void
    {
        $this->movie = $movie;
    }
}
