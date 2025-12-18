<?php

namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property string $uuid
 */
class FindUserByUuidInputDto extends InputDto
{
    public function __construct(
        protected readonly string $uuid
    ) {}
}
