<?php

declare(strict_types=1);

namespace App\Projects\Domain\Entity;

use App\General\Domain\Aggregate\AggregateRoot;
use App\General\Domain\Equatable;
use App\General\Domain\ValueObject\UserId;
use App\Projects\Domain\Event\UserProfileWasChangedEvent;
use App\Projects\Domain\Event\UserWasCreatedEvent;
use App\Projects\Domain\ValueObject\UserEmail;
use App\Projects\Domain\ValueObject\UserFirstname;
use App\Projects\Domain\ValueObject\UserLastname;
use App\Projects\Domain\ValueObject\UserPassword;
use App\Projects\Domain\ValueObject\UserProfile;

/**
 * Class User
 *
 * @package App\Projects\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class User extends AggregateRoot
{
    public function __construct(
        private readonly UserId $id,
        private readonly UserEmail $email,
        private UserProfile $profile
    ) {
    }

    public static function create(UserId $id, UserEmail $email, UserProfile $profile): self
    {
        $result = new self($id, $email, $profile);

        $result->registerEvent(new UserWasCreatedEvent(
            $result->id->value,
            $result->email->value,
            $result->profile->firstname->value,
            $result->profile->lastname->value,
            $result->profile->password->value,
            $result->id->value
        ));

        return $result;
    }

    public function changeProfile(UserFirstname $firstname, UserLastname $lastname, ?UserPassword $password): void
    {
        $profile = new UserProfile(
            $firstname,
            $lastname,
            $password ?? $this->profile->password,
        );

        if (!$this->profile->equals($profile)) {
            $this->profile = $profile;

            $this->registerEvent(new UserProfileWasChangedEvent(
                $this->id->value,
                $this->profile->firstname->value,
                $this->profile->lastname->value,
                $this->profile->password->value,
                $this->id->value
            ));
        }
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getPassword(): UserPassword
    {
        return $this->profile->password;
    }

    public function equals(Equatable $other): bool
    {
        return $other instanceof self
            && $other->id->equals($this->id)
            && $other->email->equals($this->email)
            && $other->profile->equals($this->profile);
    }
}
