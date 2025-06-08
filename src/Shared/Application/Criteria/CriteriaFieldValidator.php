<?php

declare(strict_types=1);

namespace App\Shared\Application\Criteria;

use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Operand;
use App\Shared\Domain\Exception\CriteriaFilterNotExistException;
use App\Shared\Domain\Exception\CriteriaOrderNotExistException;
use ReflectionClass;
use ReflectionException;

/**
 * Class CriteriaFieldValidator
 *
 * @package App\Shared\Application\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CriteriaFieldValidator implements CriteriaFieldValidatorInterface
{
    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     */
    public function validate(Criteria $criteria, string $class): void
    {
        $reflection = new ReflectionClass($class);
        /**
         * @var Operand $operand
         */
        foreach ($criteria->getExpression()->getOperands() as [$operator, $operand]) {
            if (!$this->checkProperty($reflection, $operand->property)) {
                throw new CriteriaFilterNotExistException($operand->property);
            }
        }

        foreach ($criteria->getOrders() as $order) {
            if (!$this->checkProperty($reflection, $order->property)) {
                throw new CriteriaOrderNotExistException($order->property);
            }
        }
    }

    private function checkProperty(ReflectionClass $reflection, string $propertyName): bool
    {
        return $reflection->hasProperty($propertyName);
    }
}
