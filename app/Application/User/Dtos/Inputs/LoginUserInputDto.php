<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property string $email
 * @property string $password
 * @property string $deviceName
 */
class LoginUserInputDto extends InputDto
{
    public function __construct(
        protected readonly string $email,
        protected readonly string $password,
        protected readonly string $deviceName,
    ) {}
}
