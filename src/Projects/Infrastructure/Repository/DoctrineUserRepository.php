<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Repository;

use App\General\Domain\ValueObject\UserId;
use App\Projects\Domain\ValueObject\UserEmail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use App\Projects\Domain\Entity\User;
use App\Projects\Domain\Repository\UserRepositoryInterface;


/**
 * Class DoctrineUserRepository
 *
 * @package App\Projects\Infrastructure\Repository
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findById(UserId $id): ?User
    {
        return $this->getRepository()->findOneBy([
            'id' => $id,
        ]);
    }

    public function findByEmail(UserEmail $email): ?User
    {
        return $this->getRepository()->findOneBy([
            'email' => $email,
        ]);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(User::class);
    }
}
