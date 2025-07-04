<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Repository;

use App\General\Infrastructure\Service\ManagedCollectionManager;
use App\Projects\Domain\Entity\Project;
use App\Projects\Domain\Entity\Request;
use App\Projects\Domain\Repository\ProjectRepositoryInterface;
use App\Projects\Domain\ValueObject\Participant;
use App\Projects\Domain\ValueObject\ProjectId;
use App\Projects\Domain\ValueObject\ProjectTask;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use ReflectionException;

/**
 * Class DoctrineProjectRepository
 *
 * @package App\Projects\Infrastructure\Repository
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class DoctrineProjectRepository implements ProjectRepositoryInterface
{
    private array $collections = [
        Participant::class => ['participants', 'projectId'],
        Request::class => ['requests', 'projectId'],
        ProjectTask::class => ['tasks', 'projectId'],
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ManagedCollectionManager $collectionManager
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function findById(ProjectId $id): ?Project
    {
        /** @var Project $object */
        $object = $this->getRepository()->findOneBy([
            'id' => $id,
        ]);

        if ($object === null) {
            return $object;
        }

        foreach ($this->collections as $className => $metadata) {
            [$propertyName, $fkName] = $metadata;

            $items = $this->entityManager->getRepository($className)
                ->findBy([
                    $fkName => $object->getId(),
                ]);
            $this->collectionManager->load($object, $propertyName, $items);
        }

        return $object;
    }

    /**
     * @throws ReflectionException
     */
    public function save(Project $project): void
    {
        $this->entityManager->persist($project);

        foreach ($this->collections as $metadata) {
            [$propertyName] = $metadata;

            $this->collectionManager->flush($project, $propertyName);
        }

        $this->entityManager->flush();
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Project::class);
    }
}
