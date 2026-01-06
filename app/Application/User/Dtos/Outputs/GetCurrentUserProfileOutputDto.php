<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;

/**
 * @property string $uuid
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 */
class GetCurrentUserProfileOutputDto extends OutputDto
{
    public function __construct(
        protected readonly string $uuid,
        protected readonly string $firstName,
        protected readonly string $lastName,
        protected readonly string $email,
    ) {}

    public static function createFrom(
        string $uuid,
        string $firstName,
        string $lastName,
        string $email,
    ): self {
        return new self(
            uuid: $uuid,
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
        );
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
        ];
    }
}
