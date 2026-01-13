<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property-read string $uuid
 */
class UserFindByUuidInputDto extends InputDto
{
    public function __construct(
        protected readonly string $uuid
    ) {}
}
