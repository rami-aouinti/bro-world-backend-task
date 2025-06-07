<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UserIsAlreadyProjectOwnerException extends DomainException
{
    public function __construct(string $userId)
    {
        $message = sprintf(
            'User "%s" is already project owner',
            $userId
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
