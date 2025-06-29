<?php

declare(strict_types=1);

namespace App\Projections\Application\Handler;

use App\General\Application\Service\CurrentUserExtractorInterface;
use App\General\Infrastructure\ValueObject\SymfonyUser;
use App\Projections\Application\Query\UserProfileQuery;
use App\Projections\Domain\Entity\UserProjection;
use App\General\Application\Bus\Query\QueryHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class UserProfileQueryHandler
 *
 * @package App\Projections\Application\Handler
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsMessageHandler]
final readonly class UserProfileQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CurrentUserExtractorInterface $userExtractor)
    {
    }

    public function __invoke(UserProfileQuery $query): SymfonyUser
    {
        return $this->userExtractor->extract();
    }
}
