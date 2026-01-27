<?php


namespace App\Application\User\Dtos\Inputs;

use App\Application\Dto\InputDto;

/**
 * @property-read int $page
 * @property-read int $perPage
 * @property-read ?string $firstName
 * @property-read ?string $lastName
 * @property-read ?string $email
 */
class UserListInputDto extends InputDto
{
    public function __construct(
        protected readonly int $page = 1,
        protected readonly int $perPage = 10,
        protected readonly ?string $firstName = null,
        protected readonly ?string $lastName = null,
        protected readonly ?string $email = null,
    ) {}
}
