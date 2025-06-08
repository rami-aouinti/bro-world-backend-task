<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

/**
 * Class LogicalOperatorEnum
 *
 * @package App\Shared\Domain\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
enum LogicalOperatorEnum
{
    case And;
    case Or;
}
