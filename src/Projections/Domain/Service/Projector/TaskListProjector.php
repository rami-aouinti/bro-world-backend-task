<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Entity\TaskListProjection;
use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Event\TaskInformationWasChangedEvent;
use App\Projections\Domain\Event\TaskLinkWasCreated;
use App\Projections\Domain\Event\TaskLinkWasDeleted;
use App\Projections\Domain\Event\TaskStatusWasChangedEvent;
use App\Projections\Domain\Event\TaskWasCreatedEvent;
use App\Projections\Domain\Event\UserProfileWasChangedEvent;
use App\Projections\Domain\Exception\ProjectionDoesNotExistException;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskListProjectionRepositoryInterface;
use App\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use App\Projections\Domain\Service\ProjectorUnitOfWork;
use App\Shared\Domain\Hashable;
use Exception;

/**
 * Class TaskListProjector
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class TaskListProjector extends Projector
{
    public function __construct(
        private readonly TaskListProjectionRepositoryInterface $repository,
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly ProjectProjectionRepositoryInterface $projectRepository,
        private readonly ProjectorUnitOfWork $unitOfWork
    ) {
    }

    public function flush(): void
    {
        /** @var TaskListProjection $item */
        foreach ($this->unitOfWork->getProjections() as $item) {
            $this->repository->save($item);
        }

        /** @var TaskListProjection $item */
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
     * @throws Exception
     */
    private function whenTaskCreated(TaskWasCreatedEvent $event): void
    {
        $projectProjection = $this->projectRepository->findById($event->projectId);
        if ($projectProjection === null) {
            throw new ProjectionDoesNotExistException($event->projectId, ProjectProjection::class);
        }

        $userProjection = $this->userRepository->findById($event->ownerId);
        if ($userProjection === null) {
            throw new ProjectionDoesNotExistException($event->ownerId, UserProjection::class);
        }

        $this->unitOfWork->createProjection(TaskListProjection::create(
            $event->getAggregateId(),
            $event->name,
            $event->startDate,
            $event->finishDate,
            $event->ownerId,
            $userProjection->getFullName(),
            $event->status,
            $projectProjection->getId()
        ));
    }

    /**
     * @throws Exception
     */
    private function whenTaskInformationChanged(TaskInformationWasChangedEvent $event): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        $projection->changeInformation($event->name, $event->startDate, $event->finishDate);
    }

    private function whenTaskStatusChanged(TaskStatusWasChangedEvent $event): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        $projection->changeStatus($event->status);
    }

    private function whenUserProfileChanged(UserProfileWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsByOwnerId($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeOwnerInformation(UserProjection::fullName($event->firstname, $event->lastname));
        }
    }

    private function whenTaskLinkCreated(TaskLinkWasCreated $event): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        $projection->createLink();
    }

    private function whenTaskLinkDeleted(TaskLinkWasDeleted $event): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        $projection->deleteLink();
    }

    /**
     * @return TaskListProjection
     */
    private function getProjectionById(string $id): Hashable
    {
        $projection = $this->repository->findById($id);

        if ($projection !== null) {
            $this->unitOfWork->loadProjection($projection);
        }

        $result = $this->unitOfWork->findProjection($id);
        if ($result === null) {
            throw new ProjectionDoesNotExistException($id, TaskListProjection::class);
        }

        return $result;
    }

    /**
     * @return TaskListProjection[]
     */
    private function getProjectionsByOwnerId(string $ownerId): array
    {
        $this->unitOfWork->loadProjections(
            $this->repository->findAllByOwnerId($ownerId)
        );

        return $this->unitOfWork->findProjections(
            fn (TaskListProjection $p) => $p->isUserOwner($ownerId)
        );
    }
}
