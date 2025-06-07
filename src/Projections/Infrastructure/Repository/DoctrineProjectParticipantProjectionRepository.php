<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Projections\Domain\Entity\ProjectParticipantProjection;
use App\Projections\Domain\Repository\ProjectParticipantProjectionRepositoryInterface;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Infrastructure\Criteria\CriteriaFinderInterface;

final readonly class DoctrineProjectParticipantProjectionRepository implements ProjectParticipantProjectionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CriteriaFinderInterface $finder
    ) {
    }

    /**
     * @return ProjectParticipantProjection[]
     */
    public function findAllByUserId(string $id): array
    {
        return $this->getRepository()->findBy([
            'userId' => $id,
        ]);
    }

    public function findByProjectAndUserId(string $projectId, string $userId): ?ProjectParticipantProjection
    {
        return $this->getRepository()->findOneBy([
            'projectId' => $projectId,
            'userId' => $userId,
        ]);
    }

    public function save(ProjectParticipantProjection $projection): void
    {
        $this->entityManager->persist($projection);
        $this->entityManager->flush();
    }

    public function delete(ProjectParticipantProjection $projection): void
    {
        $this->entityManager->remove($projection);
        $this->entityManager->flush();
    }

    /**
     * @return ProjectParticipantProjection[]
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
        return $this->entityManager->getRepository(ProjectParticipantProjection::class);
    }
}
