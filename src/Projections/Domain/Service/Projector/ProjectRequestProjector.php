<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\General\Domain\Hashable;
use App\Projections\Domain\Entity\ProjectRequestProjection;
use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Event\RequestStatusWasChangedEvent;
use App\Projections\Domain\Event\RequestWasCreatedEvent;
use App\Projections\Domain\Event\UserProfileWasChangedEvent;
use App\Projections\Domain\Exception\ProjectionDoesNotExistException;
use App\Projections\Domain\Repository\ProjectRequestProjectionRepositoryInterface;
use App\Projections\Domain\Repository\UserProjectionRepositoryInterface;
use App\Projections\Domain\Service\ProjectorUnitOfWork;
use Exception;

/**
 * Class ProjectRequestProjector
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectRequestProjector extends Projector
{
    /**
     * @var array<array-key, ProjectRequestProjection>
     */
    private array $projections = [];

    public function __construct(
        private readonly ProjectRequestProjectionRepositoryInterface $repository,
        private readonly UserProjectionRepositoryInterface $userRepository,
        private readonly ProjectorUnitOfWork $unitOfWork
    ) {
    }

    public function flush(): void
    {
        /** @var ProjectRequestProjection $item */
        foreach ($this->unitOfWork->getProjections() as $item) {
            $this->repository->save($item);
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
    private function whenRequestCreated(RequestWasCreatedEvent $event): void
    {
        $userProjection = $this->userRepository->findById($event->userId);
        if ($userProjection === null) {
            throw new ProjectionDoesNotExistException($event->userId, UserProjection::class);
        }

        $this->unitOfWork->createProjection(ProjectRequestProjection::create(
            $event->requestId,
            $event->userId,
            $userProjection->getFullName(),
            $event->status,
            $event->changeDate,
            $event->getAggregateId()
        ));
    }

    /**
     * @throws Exception
     */
    private function whenRequestStatusChanged(RequestStatusWasChangedEvent $event): void
    {
        $projection = $this->getProjectionById($event->requestId);

        if ($projection === null) {
            throw new ProjectionDoesNotExistException($event->requestId, ProjectRequestProjection::class);
        }

        $projection->changeStatus($event->status, $event->changeDate);
    }

    private function whenUserProfileChanged(UserProfileWasChangedEvent $event): void
    {
        $projections = $this->getProjectionsByUserId($event->getAggregateId());

        foreach ($projections as $projection) {
            $projection->changeUserInformation(UserProjection::fullName($event->firstname, $event->lastname));
        }
    }

    /**
     * @return ProjectRequestProjection[]
     */
    private function getProjectionsByUserId(string $userId): array
    {
        $this->unitOfWork->loadProjections(
            $this->repository->findAllByUserId($userId)
        );

        return $this->unitOfWork->findProjections(
            fn (ProjectRequestProjection $p) => $p->getUserId() === $userId
        );
    }

    /**
     * @return ProjectRequestProjection|null
     */
    private function getProjectionById(string $id): ?Hashable
    {
        $projection = $this->repository->findById($id);

        if ($projection !== null) {
            $this->unitOfWork->loadProjection($projection);
        }

        return $this->unitOfWork->findProjection($id);
    }
}
