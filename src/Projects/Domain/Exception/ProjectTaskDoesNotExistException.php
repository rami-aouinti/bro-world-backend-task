<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\General\Domain\Exception\DomainException;

final class ProjectTaskDoesNotExistException extends DomainException
{
    public function __construct(string $taskId)
    {
        $message = sprintf(
            'Project task "%s" doesn\'t exist',
            $taskId
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
