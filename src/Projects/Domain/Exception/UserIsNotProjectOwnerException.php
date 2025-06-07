<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UserIsNotProjectOwnerException extends DomainException
{
    public function __construct(string $userId)
    {
        $message = sprintf(
            'User "%s" is not project owner',
            $userId
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
