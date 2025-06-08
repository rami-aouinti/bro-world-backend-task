<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\Projections\Domain\Entity\ProjectorPosition;
use App\Projections\Domain\Repository\ProjectorPositionRepositoryInterface;
use App\Shared\Domain\ValueObject\DateTime;

/**
 * Class ProjectorPositionHandler
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectorPositionHandler implements ProjectorPositionHandlerInterface
{
    /**
     * @var array ProjectorPosition[]
     */
    private array $positions;

    public function __construct(private readonly ProjectorPositionRepositoryInterface $repository)
    {
    }

    public function getPosition(ProjectorInterface $projector): ?DateTime
    {
        return $this->getPositionInternal($projector)->getPosition();
    }

    public function storePosition(ProjectorInterface $projector, ?DateTime $position): void
    {
        $positionObject = $this->getPositionInternal($projector);

        $positionObject->adjustPosition($position);
    }

    public function isBroken(ProjectorInterface $projector): bool
    {
        return $this->getPositionInternal($projector)->isBroken();
    }

    public function markAsBroken(ProjectorInterface $projector): void
    {
        $positionObject = $this->getPositionInternal($projector);

        $positionObject->markAsBroken();
    }

    public function flushPosition(ProjectorInterface $projector): void
    {
        $position = $this->getPositionInternal($projector);

        if (!$this->isBroken($projector)) {
            $projector->flush();
        }
        $this->repository->save($position);

        unset($this->positions[$this->getProjectorName($projector)]);
    }

    private function getProjectorName(ProjectorInterface $projector): string
    {
        return $projector::class;
    }

    private function getPositionInternal(ProjectorInterface $projector): ProjectorPosition
    {
        $projectorName = $this->getProjectorName($projector);

        if (!isset($this->positions[$projectorName])) {
            $position = $this->repository->findByProjectorName($projectorName);

            if ($position === null) {
                $position = new ProjectorPosition($projectorName);
            }

            $this->positions[$projectorName] = $position;
        }

        return $this->positions[$projectorName];
    }
}
