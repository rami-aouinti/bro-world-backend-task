<?php

declare(strict_types=1);

namespace App\General\Application\Service;

use App\General\Infrastructure\ValueObject\SymfonyUser;

/**
 *
 */
interface CurrentUserExtractorInterface
{
    public function extract(): SymfonyUser;
}
