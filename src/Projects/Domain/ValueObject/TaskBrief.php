<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\ValueObject\StringValueObject;

/**
 * Class TaskBrief
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskBrief extends StringValueObject
{
    private const int MAX_LENGTH = 2000;

    protected function ensureIsValid(): void
    {
        $this->ensureValidMaxLength('Task brief', self::MAX_LENGTH);
    }
}
