<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\ProjectParticipantMemento;
use App\Projections\Domain\Entity\ProjectParticipantProjection;

/**
 * Class ProjectParticipantResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ProjectParticipantResponseDTO
{
    public string $userId;

    public string $userEmail;

    public string $userFirstname;

    public string $userLastname;

    public int $tasksCount;

    public function __construct(ProjectParticipantMemento $memento)
    {
        $this->userId = $memento->userId;
        $this->userEmail = $memento->userEmail;
        $this->userFirstname = $memento->userFirstname;
        $this->userLastname = $memento->userLastname;
        $this->tasksCount = $memento->tasksCount;
    }

    /**
     * @param ProjectParticipantProjection[] $projections
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
