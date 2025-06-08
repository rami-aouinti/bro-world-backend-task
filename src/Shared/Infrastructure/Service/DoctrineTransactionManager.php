<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Domain\Service\TransactionManagerInterface;
use Exception;

/**
 * Class DoctrineTransactionManager
 *
 * @package App\Shared\Infrastructure\Service
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class DoctrineTransactionManager implements TransactionManagerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws Exception
     */
    public function withTransaction(callable $callback): void
    {
        $this->entityManager->beginTransaction();

        try {
            $callback();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
