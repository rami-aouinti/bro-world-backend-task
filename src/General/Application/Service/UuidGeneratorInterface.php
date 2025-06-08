<?php

declare(strict_types=1);

namespace App\General\Application\Service;

/**
 *
 */
interface UuidGeneratorInterface
{
    public function generate(): string;
}
