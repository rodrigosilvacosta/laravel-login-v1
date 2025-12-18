<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property string $name
 * @property string $lastName
 * @property string $email
 * @property string $password
 */
class UpdateUserInputDto extends InputDto
{
    public function __construct(
        protected readonly string $name,
        protected readonly string $lastName,
        protected readonly string $password
    ) {}
}
