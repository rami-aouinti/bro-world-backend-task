<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\TaskLinkMemento;
use App\Projections\Domain\Entity\TaskLinkProjection;

/**
 * Class TaskLinkResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskLinkResponseDTO
{

    public string $taskId;

    public string $linkedTaskId;

    public string $linkedTaskName;

    public int $linkedTaskStatus;

    public function __construct(TaskLinkMemento $memento)
    {
        $this->taskId = $memento->taskId;
        $this->linkedTaskId = $memento->linkedTaskId;
        $this->linkedTaskName = $memento->linkedTaskName;
        $this->linkedTaskStatus = $memento->linkedTaskStatus;
    }

    /**
     * @param TaskLinkProjection[] $projections
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
