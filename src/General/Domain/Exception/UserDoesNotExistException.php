<?php

declare(strict_types=1);

namespace App\General\Domain\Exception;

use App\General\Domain\Exception\DomainException;

use function sprintf;

/**
 * Class UserDoesNotExistException
 *
 * @package App\General\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserDoesNotExistException extends DomainException
{
    public function __construct(string $userId)
    {
        $message = sprintf(
            'User "%s" doesn\'t exist',
            $userId
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
