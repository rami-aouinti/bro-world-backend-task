<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\General\Domain\Exception\DomainException;

final class UserIsNotTaskOwnerException extends DomainException
{
    public function __construct(string $userId)
    {
        $message = sprintf(
            'User "%s" is not task owner',
            $userId
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
