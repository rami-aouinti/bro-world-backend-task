<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\General\Domain\Exception\DomainException;

final class RequestDoesNotExistException extends DomainException
{
    public function __construct(string $requestId, string $projectId)
    {
        $message = sprintf(
            'Request "%s" to project "%s" doesn\'t exist',
            $requestId,
            $projectId
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
