<?php

declare(strict_types=1);

namespace App\Projects\Application\Command;

use App\Shared\Application\Bus\Command\CommandInterface;

final readonly class CreateProjectCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $finishDate,
    ) {
    }
}
