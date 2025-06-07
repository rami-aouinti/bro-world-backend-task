<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class InvalidProjectStatusTransitionException extends DomainException
{
    public function __construct(string $fromStatus, string $toStatus)
    {
        $message = sprintf(
            'Project status "%s" cannot be changed to "%s"',
            $fromStatus,
            $toStatus
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
