<?php

declare(strict_types=1);

namespace App\Projections\Domain\Exception;

use App\General\Domain\Exception\DomainException;

use function sprintf;

/**
 * Class ProjectionDoesNotExistException
 *
 * @package App\Projections\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectionDoesNotExistException extends DomainException
{
    /**
     * @param class-string $projectionName
     */
    public function __construct(string $id, string $projectionName)
    {
        $message = sprintf(
            'Projection "%s" "%s" doesn\'t exist',
            $id,
            $projectionName
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
