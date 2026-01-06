<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property string $uuid
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 */

class GetCurrentUserProfileInputDto extends InputDto
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
