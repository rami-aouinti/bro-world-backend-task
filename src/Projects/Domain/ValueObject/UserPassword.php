<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\ValueObject\StringValueObject;

final class UserPassword extends StringValueObject
{
    protected function ensureIsValid(): void
    {
        $this->ensureNotEmpty('Password');
    }
}
