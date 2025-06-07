<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Entity\TaskProjection;
use App\Projections\Domain\Event\TaskInformationWasChangedEvent;
use App\Projections\Domain\Event\TaskStatusWasChangedEvent;
use App\Projections\Domain\Event\TaskWasCreatedEvent;
use App\Projections\Domain\Exception\ProjectionDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskProjectionRepositoryInterface;
use App\Projections\Domain\Service\ProjectorUnitOfWork;
use App\Shared\Domain\Hashable;

final class TaskProjector extends Projector
{
    public function __construct(
        private readonly TaskProjectionRepositoryInterface $repository,
        private readonly ProjectProjectionRepositoryInterface $projectRepository,
        private readonly ProjectorUnitOfWork $unitOfWork
    ) {
    }

    public function flush(): void
    {
        /** @var TaskProjection $item */
        foreach ($this->unitOfWork->getProjections() as $item) {
            $this->repository->save($item);
        }

        /** @var TaskProjection $item */
        foreach ($this->unitOfWork->getDeletedProjections() as $item) {
            $this->repository->delete($item);
        }

        $this->unitOfWork->flush();
    }

    public function priority(): int
    {
        return 50;
    }

    /**
     * @throws \Exception
     */
    private function whenTaskCreated(TaskWasCreatedEvent $event, ?int $version): void
    {
        $projectProjection = $this->projectRepository->findById($event->projectId);
        if (null === $projectProjection) {
            throw new ProjectionDoesNotExistException($event->projectId, ProjectProjection::class);
        }

        $this->unitOfWork->createProjection(TaskProjection::create(
            $event->getAggregateId(),
            $event->name,
            $event->brief,
            $event->description,
            $event->startDate,
            $event->finishDate,
            $event->ownerId,
            $event->status,
            $projectProjection->getId(),
            $version
        ));
    }

    /**
     * @throws \Exception
     */
    private function whenTaskInformationChanged(TaskInformationWasChangedEvent $event, ?int $version): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        $projection->changeInformation(
            $event->name,
            $event->brief,
            $event->description,
            $event->startDate,
            $event->finishDate,
            $version
        );
    }

    private function whenTaskStatusChanged(TaskStatusWasChangedEvent $event): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        $projection->changeStatus($event->status);
    }

    /**
     * @return TaskProjection
     */
    private function getProjectionById(string $id): Hashable
    {
        $projection = $this->repository->findById($id);

        if (null !== $projection) {
            $this->unitOfWork->loadProjection($projection);
        }

        $result = $this->unitOfWork->findProjection($id);
        if (null === $result) {
            throw new ProjectionDoesNotExistException($id, TaskProjection::class);
        }

        return $result;
    }
}
