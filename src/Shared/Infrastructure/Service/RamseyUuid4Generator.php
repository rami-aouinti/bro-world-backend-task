<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use Ramsey\Uuid\UuidFactoryInterface;
use App\Shared\Application\Service\UuidGeneratorInterface as UuidGeneratorInterfaceAlias;

final readonly class RamseyUuid4Generator implements UuidGeneratorInterfaceAlias
{
    public function __construct(private UuidFactoryInterface $factory)
    {
    }

    public function generate(): string
    {
        return $this->factory->uuid4()->toString();
    }
}
