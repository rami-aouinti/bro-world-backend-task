<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service\DTO;

/**
 * Class ExceptionDTO
 *
 * @package App\General\Infrastructure\Service\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class ExceptionDTO
{
    public function __construct(
        public string $message,
        public int $httpCode,
        public ?string $file = null,
        public ?int $line = null,
        public ?array $trace = null
    ) {
    }
}
