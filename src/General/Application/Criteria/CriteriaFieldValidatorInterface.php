<?php

declare(strict_types=1);

namespace App\General\Application\Criteria;

use App\General\Domain\Criteria\Criteria;

interface CriteriaFieldValidatorInterface
{
    /**
     * @param class-string $class
     */
    public function validate(Criteria $criteria, string $class): void;
}
