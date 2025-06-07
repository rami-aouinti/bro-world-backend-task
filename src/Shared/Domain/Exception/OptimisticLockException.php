<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

/**
 * Class OptimisticLockException
 *
 * @package App\Shared\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class OptimisticLockException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Entity data is out of date', self::CODE_CONFLICT);
    }
}
