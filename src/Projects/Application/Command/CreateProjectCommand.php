<?php

declare(strict_types=1);

namespace App\Projects\Application\Command;

use App\General\Application\Bus\Command\CommandInterface;

/**
 * Class CreateProjectCommand
 *
 * @package App\Projects\Application\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
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
