<?php

declare(strict_types=1);

namespace App\General\Domain\Exception;

use App\General\Domain\Exception\DomainException;

use function sprintf;

/**
 * Class PageNotExistException
 *
 * @package App\General\Domain\Exception
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class PageNotExistException extends DomainException
{
    public function __construct(int $page)
    {
        $message = sprintf(
            'Page "%s" doesn\'t exist',
            $page
        );
        parent::__construct($message, self::CODE_NOT_FOUND);
    }
}
