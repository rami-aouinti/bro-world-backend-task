<?php

declare(strict_types=1);

namespace App\Projects\Domain\ValueObject;

use App\General\Domain\Equatable;

/**
 * Class UserProfile
 *
 * @package App\Projects\Domain\ValueObject
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final readonly class UserProfile implements Equatable
{
    public function __construct(
        public UserFirstname $firstname,
        public UserLastname $lastname,
        public UserPassword $password
    ) {
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->firstname->equals($this->firstname)
            && $other->lastname->equals($this->lastname)
            && $other->password->equals($this->password);
    }
}
