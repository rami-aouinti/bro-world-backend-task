<?php

declare(strict_types=1);

namespace App\Projections\Domain\DTO;

/**
 * Class UserMemento
 *
 * @package App\Projections\Domain\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserMemento
{
    public function __construct(
        public string $id,
        public string $email,
        public string $firstname,
        public string $lastname,
        public ?int $version
    ) {
    }
}
