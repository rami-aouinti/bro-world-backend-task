<?php

declare(strict_types=1);

namespace App\Task\Command;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Projections\Domain\Entity\ProjectListProjection;
use App\Projections\Domain\Entity\ProjectorPosition;
use App\Projections\Domain\Entity\ProjectParticipantProjection;
use App\Projections\Domain\Entity\ProjectProjection;
use App\Projections\Domain\Entity\ProjectRequestProjection;
use App\Projections\Domain\Entity\TaskLinkProjection;
use App\Projections\Domain\Entity\TaskListProjection;
use App\Projections\Domain\Entity\TaskProjection;
use App\Projections\Domain\Entity\UserProjection;
use App\Projections\Domain\Entity\UserRequestProjection;
use App\Projections\Domain\Service\Projector\ProjectionistInterface;

use function sprintf;

/**
 * Class ReloadProjectionsCommand
 *
 * @package App\Task\Command
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[AsCommand(name: 'projections:reload', description: 'Reload all projections')]
final class ReloadProjectionsCommand extends Command
{
    private const array PROJECTION_CLASSES = [
        ProjectListProjection::class,
        ProjectParticipantProjection::class,
        ProjectProjection::class,
        ProjectRequestProjection::class,
        TaskLinkProjection::class,
        TaskListProjection::class,
        TaskProjection::class,
        UserProjection::class,
        UserRequestProjection::class,
        ProjectorPosition::class,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectionistInterface $projectionist
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle(
            $input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output
        );
        $io->success('Reloading all projections.');

        $connection = $this->entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

        foreach (self::PROJECTION_CLASSES as $class) {
            $table = $this->entityManager->getClassMetadata($class)->getTableName();
            if ($output->isVeryVerbose()) {
                $io->comment(sprintf('Truncating "%s" table for class "%s"', $table, $class));
            }
            $query = $dbPlatform?->getTruncateTableSQL($table);
            $connection->executeStatement($query);
        }

        if ($output->isVeryVerbose()) {
            $io->comment('Consuming messages from event stream');
        }
        $this->projectionist->projectAll();

        return 0;
    }
}
