<?php

declare(strict_types=1);

namespace App\Shared\Application\Criteria;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Operand;
use App\Shared\Domain\Criteria\OperatorEnum;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\Exception\CriteriaFilterOperatorNotExistException;

final class CriteriaFromQueryBuilder implements CriteriaFromQueryBuilderInterface
{
    public function build(Criteria $criteria, QueryCriteriaDTO $dto): Criteria
    {
        foreach ($dto->filters as $filter) {
            $operator = OperatorEnum::tryFrom(mb_strtolower($filter->operator));
            if (null === $operator) {
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
