<?php

declare(strict_types=1);

namespace App\General\Application\Service;

use App\General\Infrastructure\ValueObject\SymfonyUser;

/**
 * Class CurrentUserExtractor
 *
 * @package App\General\Application\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class CurrentUserExtractor implements CurrentUserExtractorInterface
{
    public function __construct(
        private AuthenticatorServiceInterface $authenticator,
    ) {
    }

    public function extract(): SymfonyUser
    {
        return $this->authenticator->getSymfonyUser();
    }
}
