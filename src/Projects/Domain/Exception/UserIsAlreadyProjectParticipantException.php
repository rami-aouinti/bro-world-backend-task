<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

use function sprintf;

/**
 * Class UserIsAlreadyProjectParticipantException
 *
 * @package App\Projects\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserIsAlreadyProjectParticipantException extends DomainException
{
    public function __construct(string $userId)
    {
        $message = sprintf(
            'User "%s" is already project participant',
            $userId
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
