<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Criteria;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Shared\Domain\Criteria\Criteria;

interface DoctrineExpressionFromCriteriaBuilderInterface
{
    public function build(EntityRepository $repository, Criteria $criteria, string $alias = 't'): QueryBuilder;
}
