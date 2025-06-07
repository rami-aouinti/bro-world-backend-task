<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

interface ExceptionToHttpCodeMapperInterface
{
    public function getHttpCode(\Throwable $exception): int;
}
