<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\UserProfileQuery;
use App\Projections\Domain\Entity\UserProjection;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;

/**
 * Class UserProfileQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct()
    {
    }

    public function __invoke(SymfonyUser $user, UserProfileQuery $query): SymfonyUser
    {
        return $user;
    }
}
