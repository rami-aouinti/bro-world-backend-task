<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\DTO;

use OpenApi\Attributes as OA;
use App\Projections\Domain\DTO\UserRequestMemento;
use App\Projections\Domain\Entity\UserRequestProjection;

/**
 * Class UserRequestResponseDTO
 *
 * @package App\Projections\Infrastructure\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserRequestResponseDTO
{
    public string $id;

    public int $status;

    public string $changeDate;

    public string $projectId;

    public string $projectName;

    public function __construct(UserRequestMemento $memento)
    {
        $this->id = $memento->id;
        $this->status = $memento->status;
        $this->changeDate = $memento->changeDate;
        $this->projectId = $memento->projectId;
        $this->projectName = $memento->projectName;
    }

    /**
     * @param UserRequestProjection[] $projections
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
