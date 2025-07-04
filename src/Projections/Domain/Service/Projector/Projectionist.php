<?php

declare(strict_types=1);

namespace App\Projections\Domain\Service\Projector;

use App\General\Domain\Service\TransactionManagerInterface;
use App\Projections\Domain\DTO\ProjectionistResultDTO;
use App\Projections\Domain\Service\EventStore\EventStoreInterface;
use Exception;

/**
 * Class Projectionist
 *
 * @package App\Projections\Domain\Service\Projector
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class Projectionist implements ProjectionistInterface
{
    private array $projectors;

    /**
     * @param iterable<int, ProjectorInterface> $projectors
     */
    public function __construct(
        iterable $projectors,
        private EventStoreInterface $eventStore,
        private ProjectorPositionHandlerInterface $positionHandler,
        private TransactionManagerInterface $transactionManager
    ) {
        $this->projectors = $this->prioritizeProjectors($projectors);
    }

    /**
     * @return ProjectionistResultDTO[]
     *
     * @throws Exception
     */
    public function projectAll(): array
    {
        $result = [];

        foreach ($this->projectors as $projector) {
            if ($this->positionHandler->isBroken($projector)) {
                $result[] = new ProjectionistResultDTO($projector::class, 0, true);
                continue;
            }

            $position = $this->positionHandler->getPosition($projector);
            $streamInfo = $this->eventStore->getStreamInfo($position);

            if ($streamInfo->stream->eventCount() === 0) {
                $result[] = new ProjectionistResultDTO($projector::class, 0);
                continue;
            }

            try {
                $this->transactionManager->withTransaction(function () use ($streamInfo, $projector) {
                    while (!$streamInfo->stream->next()) {
                        $projector->projectWhen($streamInfo->stream->next());
                    }

                    $this->positionHandler->storePosition($projector, $streamInfo->lastPosition);
                    $this->positionHandler->flushPosition($projector);
                });
            } catch (Exception $e) {
                $this->positionHandler->markAsBroken($projector);
                $this->positionHandler->flushPosition($projector);
                throw $e;
            }

            $result[] = new ProjectionistResultDTO($projector::class, $streamInfo->stream->eventCount());
        }

        return $result;
    }

    /**
     * @param iterable<int, ProjectorInterface> $projectorsGenerator
     *
     * @return ProjectorInterface[]
     */
    private function prioritizeProjectors(iterable $projectorsGenerator): array
    {
        $projectors = [...$projectorsGenerator];
        usort($projectors, static function (ProjectorInterface $left, ProjectorInterface $right) {
            if ($left->priority() === $right->priority()) {
                return 0;
            }

            return ($left->priority() < $right->priority()) ? 1 : -1;
        });

        return $projectors;
    }
}
