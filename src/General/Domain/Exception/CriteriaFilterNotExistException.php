<?php

declare(strict_types=1);

namespace App\General\Domain\Exception;

use App\General\Domain\Exception\DomainException;

use function sprintf;

/**
 * Class CriteriaFilterNotExistException
 *
 * @package App\General\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class CriteriaFilterNotExistException extends DomainException
{
    public function __construct(string $field)
    {
        $message = sprintf(
            'Filter field "%s" doesn\'t exist',
            $field
        );
        parent::__construct($message, self::CODE_FORBIDDEN);
    }
}
