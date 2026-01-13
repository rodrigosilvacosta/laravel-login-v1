<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;

/**
 * @property-read string $uuid
 */
class UserRegisterOutputDto extends OutputDto
{
    public function __construct(
        protected readonly string $uuid
    ) {}

    public static function createFrom(string $uuid): self
    {
        return new self($uuid);
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
        ];
    }
}
