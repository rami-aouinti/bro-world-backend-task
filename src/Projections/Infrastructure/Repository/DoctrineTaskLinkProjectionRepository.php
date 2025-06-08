<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\Repository;

use App\General\Domain\Criteria\Criteria;
use App\General\Infrastructure\Criteria\CriteriaFinderInterface;
use App\Projections\Domain\Entity\TaskLinkProjection;
use App\Projections\Domain\Repository\TaskLinkProjectionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineTaskLinkProjectionRepository implements TaskLinkProjectionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CriteriaFinderInterface $finder
    ) {
    }

    /**
     * @return TaskLinkProjection[]
     */
    public function findAllByLinkedTaskId(string $id): array
    {
        return $this->getRepository()->findBy([
            'linkedTaskId' => $id,
        ]);
    }

    public function findByTaskAndLinkedTaskId(string $taskId, string $linkedTaskId): ?TaskLinkProjection
    {
        return $this->getRepository()->findOneBy([
            'taskId' => $taskId,
            'linkedTaskId' => $linkedTaskId,
        ]);
    }

    public function save(TaskLinkProjection $projection): void
    {
        $this->entityManager->persist($projection);
        $this->entityManager->flush();
    }

    public function delete(TaskLinkProjection $projection): void
    {
        $this->entityManager->remove($projection);
        $this->entityManager->flush();
    }

    /**
     * @return TaskLinkProjection[]
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
        return $this->entityManager->getRepository(TaskLinkProjection::class);
    }
}
