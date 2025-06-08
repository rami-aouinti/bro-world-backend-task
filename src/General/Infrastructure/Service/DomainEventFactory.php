<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\General\Domain\Event\DomainEventInterface;
use App\General\Domain\Service\DomainEventFactoryInterface;

/**
 * Class DomainEventFactory
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class DomainEventFactory implements DomainEventFactoryInterface
{
    public function __construct(
        private DomainEventMapperInterface $mapper
    ) {
    }

    /**
     * @return DomainEventInterface[]
     */
    public function create(
        string $eventName,
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): array {
        /** @var DomainEventInterface[] $classes */
        $classes = $this->mapper->getEventClasses($eventName);

        $result = [];
        foreach ($classes as $class) {
            $result[] = $class::fromPrimitives(
                $aggregateId,
                $body,
                $performerId,
                $occurredOn
            );
        }

        return $result;
    }
}
