<?php

declare(strict_types=1);

namespace App\Shared\Domain\Criteria;

enum LogicalOperatorEnum
{
    case And;
    case Or;
}
