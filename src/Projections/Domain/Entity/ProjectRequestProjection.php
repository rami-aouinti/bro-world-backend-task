<?php

declare(strict_types=1);

namespace App\Projections\Domain\Entity;

use App\General\Domain\Hashable;
use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\DTO\ProjectRequestMemento;

/**
 * Class ProjectRequestProjection
 *
 * @package App\Projections\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectRequestProjection implements Hashable
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private string $userFullName,
        private int $status,
        private DateTime $changeDate,
        private readonly string $projectId
    ) {
    }

    public function getHash(): string
    {
        return $this->id;
    }

    public static function create(
        string $id,
        string $userId,
        string $userFullName,
        string $status,
        string $changeDate,
        string $projectId
    ): self {
        return new self(
            $id,
            $userId,
            $userFullName,
            (int) $status,
            new DateTime($changeDate),
            $projectId
        );
    }

    public function changeStatus(string $status, string $changeDate): void
    {
        $this->status = (int) $status;
        $this->changeDate = new DateTime($changeDate);
    }

    public function changeUserInformation(string $fullName): void
    {
        $this->userFullName = $fullName;
    }

    public function createMemento(): ProjectRequestMemento
    {
        return new ProjectRequestMemento(
            $this->id,
            $this->userId,
            $this->userFullName,
            $this->status,
            $this->changeDate->getValue()
        );
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
