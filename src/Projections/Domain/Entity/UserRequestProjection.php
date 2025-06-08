<?php

declare(strict_types=1);

namespace App\Projections\Domain\Entity;

use App\General\Domain\Hashable;
use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\DTO\UserRequestMemento;

/**
 * Class UserRequestProjection
 *
 * @package App\Projections\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserRequestProjection implements Hashable
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private int $status,
        private DateTime $changeDate,
        private readonly string $projectId,
        private string $projectName
    ) {
    }

    public function getHash(): string
    {
        return $this->id;
    }

    public static function create(
        string $id,
        string $userId,
        string $status,
        string $changeDate,
        string $projectId,
        string $projectName
    ): self {
        return new self(
            $id,
            $userId,
            (int) $status,
            new DateTime($changeDate),
            $projectId,
            $projectName
        );
    }

    public function changeStatus(string $status, string $changeDate): void
    {
        $this->status = (int) $status;
        $this->changeDate = new DateTime($changeDate);
    }

    public function changeProjectInformation(string $name): void
    {
        $this->projectName = $name;
    }

    public function createMemento(): UserRequestMemento
    {
        return new UserRequestMemento(
            $this->id,
            $this->status,
            $this->changeDate->getValue(),
            $this->projectId,
            $this->projectName
        );
    }

    public function isForProject(string $projectId): bool
    {
        return $this->projectId === $projectId;
    }
}
