<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\TaskListMemento;
use App\Projections\Domain\Entity\TaskListProjection;

/**
 * Class TaskListResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskListResponseDTO
{
    public string $id;

    public string $name;

    public string $startDate;

    public string $finishDate;

    public string $ownerId;

    public string $ownerFullName;

    public int $status;

    public int $linksCount;

    public function __construct(TaskListMemento $memento)
    {
        $this->id = $memento->id;
        $this->name = $memento->name;
        $this->startDate = $memento->startDate;
        $this->finishDate = $memento->finishDate;
        $this->ownerId = $memento->ownerId;
        $this->ownerFullName = $memento->ownerFullName;
        $this->status = $memento->status;
        $this->linksCount = $memento->linksCount;
    }

    /**
     * @param TaskListProjection[] $projections
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
