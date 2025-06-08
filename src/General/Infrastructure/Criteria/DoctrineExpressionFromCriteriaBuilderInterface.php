<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

use App\General\Domain\Criteria\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

interface DoctrineExpressionFromCriteriaBuilderInterface
{
    public function build(EntityRepository $repository, Criteria $criteria, string $alias = 't'): QueryBuilder;
}
