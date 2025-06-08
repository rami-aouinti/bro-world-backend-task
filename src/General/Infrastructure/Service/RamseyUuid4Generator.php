<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\General\Application\Service\UuidGeneratorInterface as UuidGeneratorInterfaceAlias;
use Ramsey\Uuid\UuidFactoryInterface;

/**
 * Class RamseyUuid4Generator
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
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
