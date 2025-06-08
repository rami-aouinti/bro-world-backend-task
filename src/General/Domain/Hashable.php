<?php

declare(strict_types=1);

namespace App\General\Domain;

/**
 *
 */
interface Hashable
{
    public function getHash(): string;
}
