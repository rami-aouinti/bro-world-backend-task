<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\General\Domain\Hashable;
use App\Projections\Domain\Entity\ProjectParticipantProjection;
use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Event\ProjectParticipantWasAddedEvent;
use App\Projections\Domain\Event\ProjectParticipantWasRemovedEvent;
use App\Projections\Domain\Event\TaskWasCreatedEvent;
use App\Projections\Domain\Event\UserProfileWasChangedEvent;
use App\Projections\Domain\Exception\ProjectionDoesNotExistException;
use App\Projections\Domain\Repository\ProjectParticipantProjectionRepositoryInterface;
use App\Projections\Domain\Repository\TaskListProjectionRepositoryInterface;
use App\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use App\Projections\Domain\Service\ProjectorUnitOfWork;

/**
 * Class ProjectParticipantProjector
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectParticipantProjector extends Projector
{
    public function __construct(
        private readonly ProjectParticipantProjectionRepositoryInterface $repository,
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly TaskListProjectionRepositoryInterface $taskRepository,
        private readonly ProjectorUnitOfWork $unitOfWork
    ) {
    }

    public function flush(): void
    {
        /** @var ProjectParticipantProjection $item */
        foreach ($this->unitOfWork->getProjections() as $item) {
            $this->repository->save($item);
        }

        /** @var ProjectParticipantProjection $item */
        foreach ($this->unitOfWork->getDeletedProjections() as $item) {
            $this->repository->delete($item);
        }

        $this->unitOfWork->flush();
    }

    public function priority(): int
    {
        return 50;
    }

    private function whenProjectParticipantAdded(ProjectParticipantWasAddedEvent $event): void
    {
        $userProjection = $this->userRepository->findById($event->participantId);
        if ($userProjection === null) {
            throw new ProjectionDoesNotExistException($event->participantId, UserProjection::class);
        }

        $tasksCount = $this->taskRepository->countByProjectAndOwnerId($event->getAggregateId(), $event->participantId);

        $this->unitOfWork->createProjection(ProjectParticipantProjection::create(
            $event->participantId,
            $event->getAggregateId(),
            $userProjection->getEmail(),
            $userProjection->getFirstname(),
            $userProjection->getLastname(),
            $tasksCount
        ));
    }

    private function whenProjectParticipantRemoved(ProjectParticipantWasRemovedEvent $event): void
    {
        $projection = $this->getProjectionByProjectAndUserId($event->getAggregateId(), $event->participantId);
        if ($projection === null) {
            return;
        }

        $this->unitOfWork->deleteProjection($projection);
    }

    private function whenUserProfileChanged(UserProfileWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsByUserId($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeUserInformation($event->firstname, $event->lastname);
        }
    }

    private function whenTaskCreated(TaskWasCreatedEvent $event): void
    {
        $projection = $this->getProjectionByProjectAndUserId($event->projectId, $event->ownerId);
        if ($projection === null) {
            return;
        }

        $projection->addTask();
    }

    /**
     * @return ProjectParticipantProjection[]
     */
    private function getProjectionsByUserId(string $userId): array
    {
        $this->unitOfWork->loadProjections(
            $this->repository->findAllByUserId($userId)
        );

        return $this->unitOfWork->findProjections(
            fn (ProjectParticipantProjection $p) => $p->isForUser($userId)
        );
    }

    /**
     * @return ProjectParticipantProjection|null
     */
    private function getProjectionByProjectAndUserId(string $projectId, string $userId): ?Hashable
    {
        $projection = $this->repository->findByProjectAndUserId($projectId, $userId);

        if ($projection !== null) {
            $this->unitOfWork->loadProjection($projection);
        }

        return $this->unitOfWork->findProjection(
            ProjectParticipantProjection::hash($projectId, $userId)
        );
    }
}
