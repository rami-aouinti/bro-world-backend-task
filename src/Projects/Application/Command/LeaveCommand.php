<?php

declare(strict_types=1);

namespace App\Projects\Application\Command;

use App\Shared\Application\Bus\Command\CommandInterface;

/**
 * Class LeaveCommand
 *
 * @package App\Projects\Application\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class LeaveCommand implements CommandInterface
{
    public function __construct(public string $projectId)
    {
    }
}
