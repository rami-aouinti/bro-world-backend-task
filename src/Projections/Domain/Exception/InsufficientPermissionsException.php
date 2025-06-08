<?php

declare(strict_types=1);

namespace App\Projections\Domain\Exception;

use App\General\Domain\Exception\DomainException;

/**
 * Class InsufficientPermissionsException
 *
 * @package App\Projections\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class InsufficientPermissionsException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
