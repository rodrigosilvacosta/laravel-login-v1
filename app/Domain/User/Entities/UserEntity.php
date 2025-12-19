<?php

namespace App\Domain\User\Entities;

use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\Shared\ValueObjects\FirstName;
use App\Domain\Shared\ValueObjects\LastName;
use App\Domain\User\ValueObjects\UserUuid;

/**
 * @property int $id
 * @property UserUuid $uuid
 * @property FirstName $firstName
 * @property LastName $lastName
 * @property Email $email
 */
class UserEntity
{
    use PropertyAccessTrait;

    public function __construct(
        private readonly ?int $id,
        private readonly UserUuid $uuid,
        private readonly FirstName $firstName,
        private readonly LastName $lastName,
        private readonly Email $email
    ) {}

    public static function register(
        string $firstName,
        string $lastName,
        string $email
    ): self {
        return new self(
            id: null,
            uuid: UserUuid::generate(),
            firstName: FirstName::create($firstName),
            lastName: LastName::create($lastName),
            email: Email::create($email)
        );
    }

    public static function createFromPrimitives(
        int $id,
        string $uuid,
        string $firstName,
        string $lastName,
        string $email
    ): self {
        return new self(
            id: $id,
            uuid: UserUuid::fromUuid($uuid),
            firstName: FirstName::create($firstName),
            lastName: LastName::create($lastName),
            email: Email::create($email)
        );
    }

    public function updatePersonalInfo(
        ?string $firstName = null,
        ?string $lastName = null
    ): self {
        return new self(
            id: $this->id,
            uuid: $this->uuid,
            firstName: $firstName ? FirstName::create($firstName) : $this->firstName,
            lastName: $lastName ? LastName::create($lastName) : $this->lastName,
            email: $this->email
        );
    }
}
