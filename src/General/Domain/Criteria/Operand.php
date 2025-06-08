<?php

declare(strict_types=1);

namespace App\General\Domain\Criteria;

use LogicException;

use function gettype;
use function in_array;
use function is_array;
use function sprintf;

/**
 * Class Operand
 *
 * @package App\General\Domain\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class Operand
{
    public function __construct(
        public string $property,
        public OperatorEnum $operator,
        public mixed $value
    ) {
        $this->ensureIsValidValueType($this->operator, $this->value);
    }

    private function ensureIsValidValueType(OperatorEnum $operator, mixed $value): void
    {
        $isArrayOperator = in_array($operator, [OperatorEnum::In, OperatorEnum::NotIn], true);
        if (($isArrayOperator && !is_array($value)) || (!$isArrayOperator && is_array($value))) {
            throw new LogicException(sprintf('Invalid criteria value type "%s"', gettype($value)));
        }
    }
}
