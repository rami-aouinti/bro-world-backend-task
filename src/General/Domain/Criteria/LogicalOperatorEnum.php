<?php

declare(strict_types=1);

namespace App\General\Domain\Criteria;

/**
 * Class LogicalOperatorEnum
 *
 * @package App\General\Domain\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
enum LogicalOperatorEnum
{
    case And;
    case Or;
}
