<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\InvalidArgumentException;

use function sprintf;

/**
 * Class Email
 *
 * @package App\Shared\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class Email extends StringValueObject
{
    protected function ensureValidEmail(string $attributeName): void
    {
        if (!empty($this->value) && !filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid email address.', $attributeName));
        }
    }

    protected function ensureIsValid(): void
    {
        $this->ensureValidEmail('Email');
    }
}
