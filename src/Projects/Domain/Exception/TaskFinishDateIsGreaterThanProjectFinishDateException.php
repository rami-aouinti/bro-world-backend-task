<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\General\Domain\Exception\DomainException;

final class TaskFinishDateIsGreaterThanProjectFinishDateException extends DomainException
{
    public function __construct(string $projectFinishDate, string $finishDate)
    {
        $message = sprintf(
            'Task finish date "%s" is greater than project finish date "%s"',
            $finishDate,
            $projectFinishDate
        );
        parent::__construct($message, self::CODE_UNPROCESSABLE_ENTITY);
    }
}
