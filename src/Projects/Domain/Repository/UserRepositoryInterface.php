<?php

declare(strict_types=1);

namespace App\Projects\Domain\Repository;

use App\General\Domain\ValueObject\UserId;
use App\Projects\Domain\Entity\User;
use App\Projects\Domain\ValueObject\UserEmail;

/**
 *
 */
interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByEmail(UserEmail $email): ?User;

    public function save(User $user): void;
}
