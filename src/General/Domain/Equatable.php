<?php

declare(strict_types=1);

namespace App\General\Domain;

/**
 *
 */
interface Equatable
{
    public function equals(Equatable $other): bool;
}
