<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Event\UserProfileWasChangedEvent;
use App\Projections\Domain\Event\UserWasCreatedEvent;
use App\Projections\Domain\Exception\ProjectionDoesNotExistException;
use App\Projections\Domain\Repository\UserProjectionRepositoryInterface;

/**
 * Class UserProjector
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserProjector extends Projector
{
    /**
     * @var array<array-key, UserProjection>
     */
    private array $projections = [];

    public function __construct(private readonly UserProjectionRepositoryInterface $repository)
    {
    }

    public function flush(): void
    {
        foreach ($this->projections as $projection) {
            $this->repository->save($projection);
        }
    }

    public function priority(): int
    {
        return 200;
    }

    private function whenUserCreated(UserWasCreatedEvent $event, ?int $version): void
    {
        $this->projections[$event->getAggregateId()] = UserProjection::create(
            $event->getAggregateId(),
            $event->email,
            $event->firstname,
            $event->lastname,
            $version
        );
    }

    private function whenUserProfileChanged(UserProfileWasChangedEvent $event, ?int $version): void
    {
        $id = $event->getAggregateId();
        $projection = $this->projections[$id] ?? $this->repository->findById($id);

        if ($projection === null) {
            throw new ProjectionDoesNotExistException($id, UserProjection::class);
        }

        $projection->changeInformation($event->firstname, $event->lastname, $version);

        $this->projections[$id] = $projection;
    }
}
