<?php

declare(strict_types=1);

namespace App\Projections\Infrastructure\Repository;

use App\General\Domain\ValueObject\DateTime;
use App\Projections\Domain\Entity\Event;
use App\Projections\Domain\Repository\EventRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class DoctrineEventRepository implements EventRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Event $event): void
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    /**
     * @return Event[]
     */
    public function findOrderedFromLastTime(?DateTime $lastDatetime): array
    {
        $criteria = new Criteria();

        if (null !== $lastDatetime) {
            $criteria->where(Criteria::expr()->gt('occurredOn', $lastDatetime));
        }
        $criteria->orderBy([
            'occurredOn' => 'ASC',
        ]);

        return $this->getRepository()->matching($criteria)->toArray();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Event::class);
    }
}
