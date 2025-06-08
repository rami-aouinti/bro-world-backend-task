<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\General\Domain\Hashable;
use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Event\ProjectInformationWasChangedEvent;
use App\Projections\Domain\Event\ProjectOwnerWasChangedEvent;
use App\Projections\Domain\Event\ProjectParticipantWasAddedEvent;
use App\Projections\Domain\Event\ProjectParticipantWasRemovedEvent;
use App\Projections\Domain\Event\ProjectStatusWasChangedEvent;
use App\Projections\Domain\Event\ProjectWasCreatedEvent;
use App\Projections\Domain\Repository\ProjectProjectionRepositoryInterface;
use App\Projections\Domain\Service\ProjectorUnitOfWork;
use Exception;

use function count;

/**
 * Class ProjectProjector
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectProjector extends Projector
{
    public function __construct(
        private readonly ProjectProjectionRepositoryInterface $repository,
        private readonly ProjectorUnitOfWork $unitOfWork
    ) {
    }

    public function flush(): void
    {
        /** @var ProjectProjection $item */
        foreach ($this->unitOfWork->getProjections() as $item) {
            $this->repository->save($item);
        }

        /** @var ProjectProjection $item */
        foreach ($this->unitOfWork->getDeletedProjections() as $item) {
            $this->repository->delete($item);
        }

        $this->unitOfWork->flush();
    }

    /**
     * @throws Exception
     */
    private function whenProjectCreated(ProjectWasCreatedEvent $event, ?int $version): void
    {
        $this->unitOfWork->createProjection(ProjectProjection::create(
            $event->getAggregateId(),
            $event->ownerId,
            $event->name,
            $event->description,
            $event->finishDate,
            $event->ownerId,
            $event->status,
            $version
        ));
    }

    /**
     * @throws Exception
     */
    private function whenProjectInformationChanged(ProjectInformationWasChangedEvent $event, ?int $version): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeInformation(
                $event->name,
                $event->description,
                $event->finishDate,
                $version
            );
        }
    }

    private function whenProjectOwnerChanged(ProjectOwnerWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeOwner($event->ownerId);
            if ($projection->isForUser($event->ownerId)) {
                $this->unitOfWork->undeleteProjection($projection);
            }
        }
    }

    private function whenProjectStatusChanged(ProjectStatusWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsById($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeStatus($event->status);
        }
    }

    private function whenParticipantAdded(ProjectParticipantWasAddedEvent $event): void
    {
        $projection = $this->getProjectionById($event->getAggregateId());

        if ($projection === null) {
            return;
        }

        $this->unitOfWork->createProjection(
            $projection->cloneForUser($event->participantId)
        );
    }

    private function whenParticipantRemoved(ProjectParticipantWasRemovedEvent $event): void
    {
        $projection = $this->getProjectionByIdAndUserId($event->getAggregateId(), $event->participantId);
        if ($projection === null) {
            return;
        }

        $this->unitOfWork->deleteProjection($projection);
    }

    /**
     * @return ProjectProjection[]
     */
    private function getProjectionsById(string $id): array
    {
        $this->unitOfWork->loadProjections(
            $this->repository->findAllById($id)
        );

        return $this->unitOfWork->findProjections(
            fn (ProjectProjection $p) => $p->getId() === $id
        );
    }

    /**
     * @return ProjectProjection|null
     */
    private function getProjectionByIdAndUserId(string $id, string $userId): ?Hashable
    {
        $projection = $this->repository->findByIdAndUserId($id, $userId);

        if ($projection !== null) {
            $this->unitOfWork->loadProjection($projection);
        }

        return $this->unitOfWork->findProjection(
            ProjectProjection::hash($id, $userId)
        );
    }

    /**
     * @return ProjectProjection|null
     */
    private function getProjectionById(string $id): ?Hashable
    {
        $projection = $this->repository->findById($id);

        if ($projection !== null) {
            $this->unitOfWork->loadProjection($projection);
        }

        $projections = $this->unitOfWork->findProjections(
            fn (ProjectProjection $p) => $p->getId() === $id
        );

        if (count($projections) === 0) {
            return null;
        }

        return array_values($projections)[0];
    }
}
