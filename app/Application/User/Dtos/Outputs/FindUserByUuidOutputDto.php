<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;

class FindUserByUuidOutputDto extends OutputDto
{
    public function __construct(
        protected readonly string $uuid,
        protected readonly string $firstName,
        protected readonly string $lastName,
        protected readonly string $email
    ) {}

    public static function createFrom(
        string $uuid,
        string $firstName,
        string $lastName,
        string $email
    ): self {
        return new self($uuid, $firstName, $lastName, $email);
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
