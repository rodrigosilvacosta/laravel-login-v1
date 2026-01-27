<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property-read string $uuid
 * @property-read string $firstName
 * @property-read string $lastName
 * @property-read string $email
 */
class UserGetCurrentProfileInputDto extends InputDto
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
}
