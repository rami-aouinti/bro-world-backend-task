<?php

declare(strict_types=1);

namespace App\Projections\Domain\Entity;

use App\General\Domain\Event\DomainEventInterface;
use App\General\Domain\Service\DomainEventFactoryInterface;
use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\DTO\DomainEventEnvelope;
use Exception;
use JsonException;

/**
 * Class Event
 *
 * @package App\Projections\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class Event
{
    public function __construct(
        private string $id,
        private string $name,
        private string $aggregateId,
        private string $body,
        private string $performerId,
        private DateTime $occurredOn,
        private ?int $version
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromDomainEvent(string $id, DomainEventInterface $domainEvent, ?int $version): self
    {
        return new self(
            $id,
            $domainEvent::getEventName(),
            $domainEvent->getAggregateId(),
            json_encode($domainEvent->toPrimitives(), JSON_THROW_ON_ERROR),
            $domainEvent->getPerformerId(),
            new DateTime($domainEvent->getOccurredOn()),
            $version
        );
    }

    /**
     * @param DomainEventFactoryInterface $eventFactory
     *
     * @throws JsonException
     * @return DomainEventEnvelope[]
     */
    public function createEventEnvelope(DomainEventFactoryInterface $eventFactory): array
    {
        $domainEvents = $eventFactory->create(
            $this->name,
            $this->aggregateId,
            json_decode($this->body, true, 512, JSON_THROW_ON_ERROR),
            $this->performerId,
            $this->occurredOn->getValue()
        );

        $result = [];

        foreach ($domainEvents as $domainEvent) {
            $result[] = new DomainEventEnvelope($domainEvent, $this->version);
        }

        return $result;
    }
}
