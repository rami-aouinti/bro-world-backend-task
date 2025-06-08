<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

/**
 *
 */
interface ExceptionToHttpCodeMapperInterface
{
    public function getHttpCode(\Throwable $exception): int;
}
