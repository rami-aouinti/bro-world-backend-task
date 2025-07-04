<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use App\Projections\Domain\Entity\ProjectorPosition;
use App\Projections\Domain\Repository\ProjectorPositionRepositoryInterface;

final readonly class DoctrineProjectorPositionRepository implements ProjectorPositionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findByProjectorName(string $name): ?ProjectorPosition
    {
        return $this->getRepository()->findOneBy([
            'projectorName' => $name,
        ]);
    }

    public function save(ProjectorPosition $position): void
    {
        $this->entityManager->persist($position);
        $this->entityManager->flush();
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(ProjectorPosition::class);
    }
}
