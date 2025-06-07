<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\ProjectRequestMemento;
use App\Projections\Domain\Entity\ProjectRequestProjection;

/**
 * Class ProjectRequestResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectRequestResponseDTO
{
    public string $id;

    public string $userId;

    public string $userFullName;

    public int $status;

    public string $changeDate;

    public function __construct(ProjectRequestMemento $memento)
    {
        $this->id = $memento->id;
        $this->userId = $memento->userId;
        $this->userFullName = $memento->userFullName;
        $this->status = $memento->status;
        $this->changeDate = $memento->changeDate;
    }

    /**
     * @param ProjectRequestProjection[] $projections
     *
     * @return self[]
     */
    public static function createList(array $projections): array
    {
        $result = [];

        foreach ($projections as $projection) {
            $result[] = new self($projection->createMemento());
        }

        return $result;
    }
}
