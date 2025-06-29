<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\UserMemento;
use App\Projections\Domain\Entity\UserProjection;

/**
 * Class UserResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserResponseDTO
{
    public string $id;

    public string $email;

    public string $firstname;

    public string $lastname;

    public ?int $version;

    public function __construct(UserMemento $memento)
    {
        $this->id = $memento->id;
        $this->email = $memento->email;
        $this->firstname = $memento->firstname;
        $this->lastname = $memento->lastname;
        $this->version = $memento->version;
    }

    public static function create(SymfonyUser $projection): self
    {
        return new self($projection->createMemento());
    }
}
