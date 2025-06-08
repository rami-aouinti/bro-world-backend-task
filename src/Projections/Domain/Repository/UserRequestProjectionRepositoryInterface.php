<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\General\Domain\Criteria\Criteria;
use App\Projections\Domain\Entity\UserRequestProjection;

/**
 * @method findAllByCriteria(Criteria $criteria): UserRequestProjection[]
 */
interface UserRequestProjectionRepositoryInterface extends PageableRepositoryInterface
{
    public function findById(string $id): ?UserRequestProjection;

    /**
     * @return UserRequestProjection[]
     */
    public function findAllByProjectId(string $id): array;

    public function save(UserRequestProjection $projection): void;
}
