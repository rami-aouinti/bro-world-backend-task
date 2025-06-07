<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\Entity\ProjectListProjection;
use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Event\ProjectInformationWasChangedEvent;
use App\Projections\Domain\Event\ProjectOwnerWasChangedEvent;
use App\Projections\Domain\Event\ProjectParticipantWasAddedEvent;
use App\Projections\Domain\Event\ProjectParticipantWasRemovedEvent;
use App\Projections\Domain\Event\ProjectStatusWasChangedEvent;
use App\Projections\Domain\Event\ProjectWasCreatedEvent;
use App\Projections\Domain\Event\RequestStatusWasChangedEvent;
use App\Projections\Domain\Event\RequestWasCreatedEvent;
use App\Projections\Domain\Event\TaskWasCreatedEvent;
use App\Projections\Domain\Event\UserProfileWasChangedEvent;
use App\Projections\Domain\Event\UserWasCreatedEvent;
use App\Projections\Domain\Exception\ProjectionDoesNotExistException;
use App\Projections\Domain\Repository\ProjectListProjectionRepositoryInterface;
use App\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use App\Projections\Domain\Service\ProjectorUnitOfWork;

final class ProjectListProjector extends Projector
{
    public function __construct(
        private readonly ProjectListProjectionRepositoryInterface $repository,
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly ProjectorUnitOfWork $unitOfWork
    ) {
    }

    public function flush(): void
    {
        /** @var ProjectListProjection $item */
        foreach ($this->unitOfWork->getProjections() as $item) {
            $this->repository->save($item);
        }

        /** @var ProjectListProjection $item */
        foreach ($this->unitOfWork->getDeletedProjections() as $item) {
            $this->repository->delete($item);
        }

        $this->unitOfWork->flush();
    }

    /**
     * @throws \Exception
     */
    private function whenProjectCreated(ProjectWasCreatedEvent $event): void
    {
        $ownerProjection = $this->userRepository->findById($event->ownerId);
        if (null === $ownerProjection) {
            throw new ProjectionDoesNotExistException($event->ownerId, UserProjection::class);
        }

        $userProjections = $this->userRepository->findAll();

        foreach ($userProjections as $userProjection) {
            $this->unitOfWork->createProjection(ProjectListProjection::create(
                $event->getAggregateId(),
                $userProjection->getId(),
                $event->name,
                $event->finishDate,
                $event->ownerId,
                $ownerProjection->getFullName(),
                $event->status
            ));
        }
    }

    /**
     * @throws \Exception
     */
    private function whenProjectInformationChanged(ProjectInformationWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeInformation($event->name, $event->finishDate);
        }
    }

    private function whenProjectOwnerChanged(ProjectOwnerWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        $userProjection = $this->userRepository->findById($event->ownerId);
        if (null === $userProjection) {
            throw new ProjectionDoesNotExistException($event->ownerId, UserProjection::class);
        }

        foreach ($projections as $projection) {
            $projection->changeOwner($event->ownerId, $userProjection->getFullName());
        }
    }

    private function whenProjectStatusChanged(ProjectStatusWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeStatus($event->status);
        }
    }

    private function whenTaskCreated(TaskWasCreatedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->projectId);

        foreach ($projections as $projection) {
            $projection->addTask();
        }
    }

    private function whenRequestCreated(RequestWasCreatedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->createRequest($event->userId, $event->status);
        }
    }

    private function whenRequestStatusChanged(RequestStatusWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeRequestStatus($event->userId, $event->status);
        }
    }

    private function whenParticipantAdded(ProjectParticipantWasAddedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->addParticipant($event->participantId);
        }
    }

    private function whenParticipantRemoved(ProjectParticipantWasRemovedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->removeParticipant($event->participantId);
        }
    }

    private function whenUserCreated(UserWasCreatedEvent $event): void
    {
        $ownersProjects = $this->repository->findAllOwnersProjects();

        foreach ($ownersProjects as $ownersProject) {
            $this->unitOfWork->createProjection(
                $ownersProject->cloneForUser($event->getAggregateId())
            );
        }
    }

    private function whenUserProfileChanged(UserProfileWasChangedEvent $event): void
    {
        $this->unitOfWork->loadProjections(
            $this->repository->findAllByOwnerId($event->getAggregateId())
        );
        $projections = $this->unitOfWork->findProjections(
            fn (ProjectListProjection $p) => $p->isUserOwner($event->getAggregateId())
        );

        /** @var ProjectListProjection $projection */
        foreach ($projections as $projection) {
            $projection->changeOwnerFullName(UserProjection::fullName($event->firstname, $event->lastname));
        }
    }

    /**
     * @return ProjectListProjection[]
     */
    private function getProjectionsById(string $id): array
    {
        $this->unitOfWork->loadProjections(
            $this->repository->findAllById($id)
        );

        return $this->unitOfWork->findProjections(
            fn (ProjectListProjection $p) => $p->getId() === $id
        );
    }
}
