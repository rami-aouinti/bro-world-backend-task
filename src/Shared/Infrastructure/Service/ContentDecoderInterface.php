<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

interface ContentDecoderInterface
{
    public function decode(string $content): array;
}
