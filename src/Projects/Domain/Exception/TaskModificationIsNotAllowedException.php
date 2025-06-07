<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class TaskModificationIsNotAllowedException extends DomainException
{
    public function __construct(string $status)
    {
        $message = sprintf(
            'Task modification is not allowed when status is "%s"',
            $status
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
