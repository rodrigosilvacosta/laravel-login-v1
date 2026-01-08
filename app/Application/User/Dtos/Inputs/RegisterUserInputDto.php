<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property-read string $firstName
 * @property-read string $lastName
 * @property-read string $email
 * @property-read string $password
 */
class RegisterUserInputDto extends InputDto
{
    public function __construct(
        protected readonly string $firstName,
        protected readonly string $lastName,
        protected readonly string $email,
        protected readonly string $password
    ) {}
}
