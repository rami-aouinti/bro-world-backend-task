<?php

declare(strict_types=1);

namespace App\Projections\Domain\Repository;

use App\Projections\Domain\Entity\UserProjection;

/**
 *
 */
interface UserProjectionRepositoryInterface
{
    public function findById(string $id): ?UserProjection;

    /**
     * @return UserProjection[]
     */
    public function findAll(): array;

    public function save(UserProjection $projection): void;
}
