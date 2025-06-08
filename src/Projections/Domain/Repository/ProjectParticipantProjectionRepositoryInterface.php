<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Entity\ProjectParticipantProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): ProjectParticipantProjection[]
 */
interface ProjectParticipantProjectionRepositoryInterface extends PageableRepositoryInterface
{
    /**
     * @return ProjectParticipantProjection[]
     */
    public function findAllByUserId(string $id): array;

    public function findByProjectAndUserId(string $projectId, string $userId): ?ProjectParticipantProjection;

    public function save(ProjectParticipantProjection $projection): void;

    public function delete(ProjectParticipantProjection $projection): void;
}
