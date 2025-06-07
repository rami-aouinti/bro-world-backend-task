<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Event\DomainEventInterface;

use LogicException;

use function sprintf;

/**
 * Class DomainEventMapper
 *
 * @package App\Shared\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class DomainEventMapper implements DomainEventMapperInterface
{
    private array $map = [];

    public function __construct(private readonly array $events)
    {
        $this->indexMap();
    }

    /**
     * @return array<array-key, class-string>
     */
    public function getEventClasses(string $eventName): array
    {
        return $this->map[$eventName] ?? [];
    }

    private function indexMap(): void
    {
        if (empty($this->map)) {
            foreach ($this->events as $eventClass) {
                if (!is_subclass_of($eventClass, DomainEventInterface::class)) {
                    throw new LogicException(sprintf('"%s" must be instance of DomainEvent', $eventClass));
                }
                $eventName = $eventClass::getEventName();
                $this->map[$eventName][] = $eventClass;
            }
        }
    }
}
