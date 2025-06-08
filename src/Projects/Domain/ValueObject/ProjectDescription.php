<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\ValueObject\StringValueObject as StringValueObjectAlias;

/**
 * Class ProjectDescription
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectDescription extends StringValueObjectAlias
{
    private const int MAX_LENGTH = 4000;

    protected function ensureIsValid(): void
    {
        $this->ensureValidMaxLength('Project description', self::MAX_LENGTH);
    }
}
