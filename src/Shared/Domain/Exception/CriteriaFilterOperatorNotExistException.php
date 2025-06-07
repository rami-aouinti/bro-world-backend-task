<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use function sprintf;

/**
 * Class CriteriaFilterOperatorNotExistException
 *
 * @package App\Shared\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CriteriaFilterOperatorNotExistException extends DomainException
{
    public function __construct(string $operator, string $field)
    {
        $message = sprintf(
            'Filter operator "%s" for field "%s" doesn\'t exist',
            $operator,
            $field
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
