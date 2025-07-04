<?php

declare(strict_types=1);

namespace App\Projections\Domain\Event;

use App\General\Domain\Event\DomainEvent;

/**
 * Class UserWasCreatedEvent
 *
 * @package App\Projections\Domain\Event
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserWasCreatedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $password,
        string $performerId,
        string $occurredOn = null
    ) {
        parent::__construct($id, $performerId, $occurredOn);
    }

    public static function getEventName(): string
    {
        return 'user.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $performerId,
        string $occurredOn
    ): static {
        return new self(
            $aggregateId,
            $body['email'],
            $body['firstname'],
            $body['lastname'],
            $body['password'],
            $performerId,
            $occurredOn
        );
    }

    public function toPrimitives(): array
    {
        return [
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'password' => $this->password,
        ];
    }
}
