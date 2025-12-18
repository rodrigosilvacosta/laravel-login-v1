<?php

namespace App\Domain\User\Entities;

use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\Shared\ValueObjects\Name;
use App\Domain\User\ValueObjects\UserUuid;

/**
 * @property int $id
 * @property UserUuid $uuid
 * @property Name $name
 * @property Name $lastName
 * @property Email $email
 */
class UserEntity
{
    use PropertyAccessTrait;

    public function __construct(
        private readonly ?int $id,
        private readonly UserUuid $uuid,
        private readonly Name $name,
        private readonly Name $lastName,
        private readonly Email $email
    ) {}

    public static function register(
        string $name,
        string $lastName,
        string $email
    ): self {
        return new self(
            id: null,
            uuid: UserUuid::generate(),
            name: Name::create($name),
            lastName: Name::create($lastName),
            email: Email::create($email)
        );
    }

    public static function createFromPrimitives(
        int $id,
        string $uuid,
        string $name,
        string $lastName,
        string $email
    ): self {
        return new self(
            id: $id,
            uuid: UserUuid::fromUuid($uuid),
            name: Name::create($name),
            lastName: Name::create($lastName),
            email: Email::create($email)
        );
    }

    public function updatePersonalInfo(
        ?string $name = null,
        ?string $lastName = null
    ): self {
        return new self(
            id: $this->id,
            uuid: $this->uuid,
            name: $name ? Name::create($name) : $this->name,
            lastName: $lastName ? Name::create($lastName) : $this->lastName,
            email: $this->email
        );
    }
}
