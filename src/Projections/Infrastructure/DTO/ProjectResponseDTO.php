<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\ProjectMemento;
use App\Projections\Domain\Entity\ProjectProjection;

/**
 * Class ProjectResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectResponseDTO
{

    public string $id;

    public string $name;

    public string $description;

    public string $finishDate;

    public int $status;

    public bool $isOwner;

    public ?int $version;

    public function __construct(ProjectMemento $memento)
    {
        $this->id = $memento->id;
        $this->name = $memento->name;
        $this->description = $memento->description;
        $this->finishDate = $memento->finishDate;
        $this->status = $memento->status;
        $this->isOwner = $memento->isOwner;
        $this->version = $memento->version;
    }

    public static function create(ProjectProjection $projection): self
    {
        return new self($projection->createMemento());
    }
}
