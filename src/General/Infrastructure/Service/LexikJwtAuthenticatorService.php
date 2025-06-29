<?php

declare(strict_types=1);

namespace App\General\Infrastructure\Service;

use App\Projections\Domain\Entity\UserProjection;
use App\Projects\Domain\Entity\User;
use App\Projects\Domain\ValueObject\UserEmail;
use App\Projects\Domain\ValueObject\UserFirstname;
use App\Projects\Domain\ValueObject\UserLastname;
use App\Projects\Domain\ValueObject\UserPassword;
use App\Projects\Domain\ValueObject\UserProfile;
use App\Projects\Infrastructure\Repository\DoctrineUserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use LogicException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\General\Application\Service\AuthenticatorServiceInterface;
use App\General\Domain\Exception\AuthenticationException;
use App\General\Domain\ValueObject\UserId;
use App\General\Infrastructure\ValueObject\SymfonyUser;

use function sprintf;

/**
 * Class LexikJwtAuthenticatorService
 *
 * @package App\General\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class LexikJwtAuthenticatorService implements AuthenticatorServiceInterface, EventSubscriberInterface
{
    private ?string $userId = null;
    private ?string $email = null;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $avatar = null;
    private ?array $roles = null;
    private ?string $pathRegexp = null;

    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager,
        private readonly TokenExtractorInterface $tokenExtractor,
        private readonly DoctrineUserRepository $repository,
        private readonly string $path
    ) {
        $this->configurePathRegexp();
    }

    public function getUserId(): ?UserId
    {
        return new UserId($this->userId);
    }

    public function getSymfonyUser(): ?SymfonyUser
    {
        return new SymfonyUser(
            $this->userId,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->avatar,
            $this->roles
        );
    }

    public function getToken(string $id): ?string
    {
        return $this->tokenManager->create(new SymfonyUser($id, $this->email, $this->firstName, $this->lastName, $this->avatar,[]));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(KernelEvent $event): void
    {
        $uri = $event->getRequest()->getRequestUri();

        try {
            $payload = $this->extractTokenPayloadFromRequest($event->getRequest());
            $this->userId = $payload['id'];
            $this->email = $payload['email'];
            $this->firstName = $payload['firstName'];
            $this->lastName = $payload['lastName'];
            $this->avatar = $payload['avatar'];
            $this->roles = $payload['roles'];

            if(!$this->repository->findById(new UserId($this->userId))) {
                $userProjection = new User(
                    new UserId($this->userId),
                    new UserEmail($this->email),
                    new UserProfile(
                        new UserFirstname($this->firstName),
                        new UserLastname($this->lastName),
                        new UserPassword($this->userId)
                    )
                );
                $this->repository->save($userProjection);
            }

        } catch (AuthenticationException $e) {
            if (preg_match($this->pathRegexp, $uri) > 0) {
                throw $e;
            }
        }
    }

    private function extractTokenPayloadFromRequest(Request $request): array
    {
        $token = $this->tokenExtractor->extract($request);
        $token = $token === false ? '' : $token;

        try {
            $payload = $this->tokenManager->parse($token);
            if (!$payload) {
                throw new AuthenticationException('Invalid JWT Token');
            }

            return $payload;
        } catch (JWTDecodeFailureException $e) {
            if (JWTDecodeFailureException::EXPIRED_TOKEN === $e->getReason()) {
                throw new AuthenticationException('Expired token');
            }

            throw new AuthenticationException('Invalid JWT Token');
        }
    }

    private function getUserIdClaim(array $payload): string
    {
        $idClaim = $this->tokenManager->getUserIdClaim();
        if (!isset($payload[$idClaim])) {
            throw new AuthenticationException(sprintf('Invalid payload "%s"', $idClaim));
        }

        return $payload[$idClaim];
    }

    private function configurePathRegexp(): void
    {
        $this->pathRegexp = '/'.str_replace('/', '\/', $this->path).'/';
        if (@preg_match($this->pathRegexp, '') === false) {
            throw new LogicException(sprintf('Invalid path regexp "%s"', $this->path));
        }
    }
}
