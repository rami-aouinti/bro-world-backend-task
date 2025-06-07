<?php

declare(strict_types=1);

namespace App\Shared\Application\Service;

use App\Shared\Domain\ValueObject\UserId;

interface AuthenticatorServiceInterface
{
    public function getUserId(): UserId;

    public function getToken(string $id): string;
}
