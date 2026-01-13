<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property-read string $firstName
 * @property-read string $lastName
 */
class UserUpdateInputDto extends InputDto
{
    public function __construct(
        protected readonly string $firstName,
        protected readonly string $lastName
    ) {}
}
