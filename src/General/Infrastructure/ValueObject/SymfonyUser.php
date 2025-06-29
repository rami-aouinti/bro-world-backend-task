<?php

declare(strict_types=1);

namespace App\General\Infrastructure\ValueObject;

use App\Projections\Domain\DTO\UserMemento;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SymfonyUser
 *
 * @package App\General\Infrastructure\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class SymfonyUser implements UserInterface
{
    public function __construct(
        private ?string $userIdentifier,
        private ?string $email,
        private ?string $firstName,
        private ?string $lastName,
        private ?string $avatar,
        private ?array $roles
    )
    {
    }

    public function createMemento(): UserMemento
    {
        return new UserMemento(
            $this->userIdentifier,
            $this->email,
            $this->firstName,
            $this->lastName,
            1
        );
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFullName(): ?string
    {
        return $this->firstName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

}
