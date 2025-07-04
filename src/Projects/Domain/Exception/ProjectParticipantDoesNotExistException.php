<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\General\Domain\Exception\DomainException;

final class ProjectParticipantDoesNotExistException extends DomainException
{
    public function __construct(string $id)
    {
        $message = sprintf(
            'Project participant "%s" doesn\'t exist',
            $id
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
