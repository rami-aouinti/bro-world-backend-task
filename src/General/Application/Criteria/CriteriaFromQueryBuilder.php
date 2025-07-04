<?php

declare(strict_types=1);

namespace App\General\Application\Criteria;

use App\General\Domain\Criteria\Criteria;
use App\General\Domain\Criteria\Operand;
use App\General\Domain\Criteria\OperatorEnum;
use App\General\Domain\Criteria\Order;
use App\General\Domain\Exception\CriteriaFilterOperatorNotExistException;

/**
 * Class CriteriaFromQueryBuilder
 *
 * @package App\General\Application\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CriteriaFromQueryBuilder implements CriteriaFromQueryBuilderInterface
{
    public function build(Criteria $criteria, QueryCriteriaDTO $dto): Criteria
    {
        foreach ($dto->filters as $filter) {
            $operator = OperatorEnum::tryFrom(mb_strtolower($filter->operator));
            if ($operator === null) {
                throw new CriteriaFilterOperatorNotExistException($filter->operator, $filter->property);
            }

            $criteria->addOperand(new Operand($filter->property, $operator, $filter->value));
        }

        if ($dto->orders) {
            // Reset default ordering
            $criteria->resetOrders();
            foreach ($dto->orders as $name => $isAsc) {
                $criteria->addOrder(new Order((string) $name, (bool) $isAsc));
            }
        }

        $criteria->setOffset($dto->offset)
            ->setLimit($dto->limit);

        return $criteria;
    }
}
