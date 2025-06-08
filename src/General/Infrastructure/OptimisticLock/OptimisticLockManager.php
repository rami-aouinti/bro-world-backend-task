<?php

declare(strict_types=1);

namespace App\General\Infrastructure\OptimisticLock;

use App\General\Application\OptimisticLock\OptimisticLock;
use App\General\Application\OptimisticLock\OptimisticLockManagerInterface;
use App\General\Application\Service\UuidGeneratorInterface;
use App\General\Domain\Aggregate\AggregateRoot;
use App\General\Domain\Exception\OptimisticLockException;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException as DoctrineOptimisticLockException;
use Doctrine\ORM\PessimisticLockException;

/**
 * Class OptimisticLockManager
 *
 * @package App\General\Infrastructure\OptimisticLock
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class OptimisticLockManager implements OptimisticLockManagerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    /**
     * @param AggregateRoot $aggregateRoot
     * @param int           $expectedVersion
     *
     * @throws PessimisticLockException
     * @return int
     */
    public function lock(AggregateRoot $aggregateRoot, int $expectedVersion): int
    {
        $lock = $this->entityManager->getRepository(OptimisticLock::class)->findOneBy([
            'aggregateRoot' => $aggregateRoot::class,
            'aggregateId' => $aggregateRoot->getId()->value,
        ]);

        try {
            if ($lock === null) {
                $lock = new OptimisticLock($aggregateRoot::class, $aggregateRoot->getId()->value);
            } else {
                $this->entityManager->lock($lock, LockMode::OPTIMISTIC, $expectedVersion);
            }

            $lock->uuid = $this->uuidGenerator->generate();

            $this->entityManager->persist($lock);
            $this->entityManager->flush();

            return $lock->version;
        } catch (DoctrineOptimisticLockException) {
            throw new OptimisticLockException();
        }
    }
}
