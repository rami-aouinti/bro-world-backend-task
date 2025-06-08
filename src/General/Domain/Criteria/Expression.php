<?php

declare(strict_types=1);

namespace App\General\Domain\Criteria;

/**
 * Class Expression
 *
 * @package App\General\Domain\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Expression
{
    private array $operands = [];

    public function __construct(Operand $operand = null)
    {
        if ($operand !== null) {
            $this->andOperand($operand);
        }
    }

    public function andOperand(Operand $operand): self
    {
        $this->operands[] = [
            LogicalOperatorEnum::And,
            $operand,
        ];

        return $this;
    }

    public function orOperand(Operand $operand): self
    {
        $this->operands[] = [
            LogicalOperatorEnum::Or,
            $operand,
        ];

        return $this;
    }

    /**
     * @return array<array-key, array{0: LogicalOperatorEnum, 1: Operand}>
     */
    public function getOperands(): array
    {
        return $this->operands;
    }
}
