<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\Shared\Domain\ValueObject\StringValueObject;

/**
 * Class ProjectName
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectName extends StringValueObject
{
    private const int MAX_LENGTH = 255;

    protected function ensureIsValid(): void
    {
        $attributeName = 'Project name';
        $this->ensureNotEmpty($attributeName);
        $this->ensureValidMaxLength($attributeName, self::MAX_LENGTH);
    }
}
