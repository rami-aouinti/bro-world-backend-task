<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

use App\General\Domain\Criteria\Criteria;
use Doctrine\ORM\EntityRepository;

interface CriteriaFinderInterface
{
    public function findAllByCriteria(EntityRepository $repository, Criteria $criteria): array;

    public function findCountByCriteria(EntityRepository $repository, Criteria $criteria): int;

    public function findByCriteria(EntityRepository $repository, Criteria $criteria): mixed;
}
