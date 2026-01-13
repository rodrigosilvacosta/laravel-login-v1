<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;

class UserUpdateOutputDto extends OutputDto
{
    public function __construct(
        protected readonly string $uuid,
        protected readonly string $firstName,
        protected readonly string $lastName,
    ) {}

    public static function createFrom(string $uuid, string $firstName, string $lastName): self
    {
        return new self($uuid, $firstName, $lastName);
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        ];
    }
}
