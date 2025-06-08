<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

interface ContentDecoderInterface
{
    public function decode(string $content): array;
}
