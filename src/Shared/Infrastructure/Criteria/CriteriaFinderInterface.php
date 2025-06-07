<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Criteria;

use Doctrine\ORM\EntityRepository;
use App\Shared\Domain\Criteria\Criteria;

interface CriteriaFinderInterface
{
    public function findAllByCriteria(EntityRepository $repository, Criteria $criteria): array;

    public function findCountByCriteria(EntityRepository $repository, Criteria $criteria): int;

    public function findByCriteria(EntityRepository $repository, Criteria $criteria): mixed;
}
