<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
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
