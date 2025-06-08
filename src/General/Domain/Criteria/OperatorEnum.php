<?php

declare(strict_types=1);

namespace App\General\Domain\Criteria;

/**
 * Class OperatorEnum
 *
 * @package App\General\Domain\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
enum OperatorEnum: string
{
    case Equal = 'eq';
    case NotEqual = 'neq';
    case Greater = 'gt';
    case GreaterOrEqual = 'gte';
    case Less = 'lt';
    case LessOrEqual = 'lte';
    case In = 'in';
    case NotIn = 'nin';
    case Like = 'like';
}
