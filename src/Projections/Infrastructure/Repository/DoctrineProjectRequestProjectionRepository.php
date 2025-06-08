<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\Repository;

use App\General\Domain\Criteria\Criteria;
use App\General\Infrastructure\Criteria\CriteriaFinderInterface;
use App\Projections\Domain\Entity\ProjectRequestProjection;
use App\Projections\Domain\Repository\ProjectRequestProjectionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineProjectRequestProjectionRepository implements ProjectRequestProjectionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CriteriaFinderInterface $finder
    ) {
    }

    public function findById(string $id): ?ProjectRequestProjection
    {
        return $this->getRepository()->findOneBy([
            'id' => $id,
        ]);
    }

    /**
     * @return ProjectRequestProjection[]
     */
    public function findAllByUserId(string $id): array
    {
        return $this->getRepository()->findBy([
            'userId' => $id,
        ]);
    }

    public function save(ProjectRequestProjection $projection): void
    {
        $this->entityManager->persist($projection);
        $this->entityManager->flush();
    }

    /**
     * @return ProjectRequestProjection[]
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
        return $this->entityManager->getRepository(ProjectRequestProjection::class);
    }
}
