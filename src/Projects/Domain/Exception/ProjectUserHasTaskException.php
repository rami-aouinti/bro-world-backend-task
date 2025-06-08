<?php

declare(strict_types=1);

namespace App\Projects\Domain\Exception;

use App\General\Domain\Exception\DomainException;

/**
 * Class ProjectUserHasTaskException
 *
 * @package App\Projects\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class ProjectUserHasTaskException extends DomainException
{
    public function __construct(string $userId, string $projectId)
    {
        $message = sprintf(
            'User "%s" has task(s) in project "%s',
            $userId,
            $projectId
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
