<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Criteria;

use App\General\Application\Criteria\CriteriaFieldValidatorInterface;
use App\General\Domain\Criteria\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * Class CriteriaFinder
 *
 * @package App\General\Infrastructure\Criteria
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class CriteriaFinder implements CriteriaFinderInterface
{
    public function __construct(
        private DoctrineExpressionFromCriteriaBuilderInterface $builder,
        private CriteriaFieldValidatorInterface $validator
    ) {
    }

    /**
     * @param EntityRepository $repository
     * @param Criteria         $criteria
     *
     * @return array
     */
    public function findAllByCriteria(EntityRepository $repository, Criteria $criteria): array
    {
        $this->validator->validate($criteria, $repository->getClassName());

        return $this->builder->build($repository, $criteria)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param EntityRepository $repository
     * @param Criteria         $criteria
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @return int
     */
    public function findCountByCriteria(EntityRepository $repository, Criteria $criteria): int
    {
        $this->validator->validate($criteria, $repository->getClassName());

        $operands = [];
        foreach ($criteria->getExpression()->getOperands() as $item) {
            $operands[] = $item[1];
        }
        $countCriteria = new Criteria($operands);

        return $this->builder->build($repository, $countCriteria)
            ->select('count(t)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param EntityRepository $repository
     * @param Criteria         $criteria
     *
     * @throws NonUniqueResultException
     * @return mixed
     */
    public function findByCriteria(EntityRepository $repository, Criteria $criteria): mixed
    {
        $this->validator->validate($criteria, $repository->getClassName());

        return $this->builder->build($repository, $criteria)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
