<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;

class FindUserByUuidOutputDto extends OutputDto
{
    public function __construct(
        protected readonly string $uuid,
        protected readonly string $name,
        protected readonly string $lastName,
        protected readonly string $email
    ) {}

    public static function createFrom(
        string $uuid,
        string $name,
        string $lastName,
        string $email
    ): self {
        return new self($uuid, $name, $lastName, $email);
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'last_name' => $this->lastName,
            'email' => $this->email,
        ];
    }
}
