<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property-read string $email
 * @property-read string $password
 * @property-read string $deviceName
 */
class UserLoginInputDto extends InputDto
{
    public function __construct(
        protected readonly string $email,
        protected readonly string $password,
        protected readonly string $deviceName,
    ) {}
}
