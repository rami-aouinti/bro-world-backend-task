<?php

declare(strict_types=1);

namespace App\General\Domain\Exception;

use App\General\Domain\Exception\DomainException;

use function sprintf;

/**
 * Class CriteriaFilterOperatorNotExistException
 *
 * @package App\General\Domain\Exception
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
