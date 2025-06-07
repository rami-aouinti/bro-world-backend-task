<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\ValueObject\StringValueObject;

final class TaskBrief extends StringValueObject
{
    private const MAX_LENGTH = 2000;

    protected function ensureIsValid(): void
    {
        $this->ensureValidMaxLength('Task brief', self::MAX_LENGTH);
    }
}
