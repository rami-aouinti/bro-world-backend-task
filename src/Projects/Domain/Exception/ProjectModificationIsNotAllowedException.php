<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProjectModificationIsNotAllowedException extends DomainException
{
    public function __construct(string $status)
    {
        $message = sprintf(
            'Project modification is not allowed when status is "%s"',
            $status
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
