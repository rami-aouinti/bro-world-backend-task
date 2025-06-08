<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\Repository;

use App\General\Domain\Criteria\Criteria;
use App\General\Infrastructure\Criteria\CriteriaFinderInterface;
use App\Projections\Domain\Entity\UserRequestProjection;
use App\Projections\Domain\Repository\UserRequestProjectionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineUserRequestProjectionRepository implements UserRequestProjectionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CriteriaFinderInterface $finder,
    ) {
    }

    public function findById(string $id): ?UserRequestProjection
    {
        return $this->getRepository()->findOneBy([
            'id' => $id,
        ]);
    }

    /**
     * @return UserRequestProjection[]
     */
    public function findAllByProjectId(string $id): array
    {
        return $this->getRepository()->findBy([
            'projectId' => $id,
        ]);
    }

    public function save(UserRequestProjection $projection): void
    {
        $this->entityManager->persist($projection);
        $this->entityManager->flush();
    }

    /**
     * @return UserRequestProjection[]
     */
    public function findAllByCriteria(Criteria $criteria): array
    {
        return $this->finder->findAllByCriteria($this->getRepository(), $criteria);
    }

    public function findCountByCriteria(Criteria $criteria): int
    {
        return $this->finder->findCountByCriteria($this->getRepository(), $criteria);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(UserRequestProjection::class);
    }
}
