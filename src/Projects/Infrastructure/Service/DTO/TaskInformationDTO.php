<?php

declare(strict_types=1);

namespace App\Projects\Infrastructure\Service\DTO;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TaskInformationDTO
 *
 * @package App\Projects\Infrastructure\Service\DTO
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class TaskInformationDTO
{
    public function __construct(

        #[Groups(['create', 'update'])]
        #[Assert\NotBlank]
        public string $name = '',

        #[Groups(['create', 'update'])]
        public string $brief = '',

        #[Groups(['create', 'update'])]
        public string $description = '',

        #[Groups(['create', 'update'])]
        public string $startDate = '',

        #[Assert\NotBlank]
        public string $finishDate = '',

        #[Groups(['update'])]
        #[Assert\NotBlank]
        public string $version = ''
    ) {
    }
}
