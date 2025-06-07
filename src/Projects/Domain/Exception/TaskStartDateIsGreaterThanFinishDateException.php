<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class TaskStartDateIsGreaterThanFinishDateException extends DomainException
{
    public function __construct(string $startDate, string $finishDate)
    {
        $message = sprintf(
            'Task start date "%s" is greater than finish date "%s"',
            $startDate,
            $finishDate
        );
        parent::__construct($message, self::CODE_UNPROCESSABLE_ENTITY);
    }
}
