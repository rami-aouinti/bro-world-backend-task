<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\TaskMemento;
use App\Projections\Domain\Entity\TaskProjection;

/**
 * Class TaskResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskResponseDTO
{
    public string $id;

    public string $name;

    public string $brief;

    public string $description;

    public string $startDate;

    public string $finishDate;

    public string $ownerId;

    public int $status;

    public ?int $version;

    public function __construct(TaskMemento $memento)
    {
        $this->id = $memento->id;
        $this->name = $memento->name;
        $this->brief = $memento->brief;
        $this->description = $memento->description;
        $this->startDate = $memento->startDate;
        $this->finishDate = $memento->finishDate;
        $this->ownerId = $memento->ownerId;
        $this->status = $memento->status;
        $this->version = $memento->version;
    }

    public static function create(TaskProjection $projection): self
    {
        return new self($projection->createMemento());
    }
}
