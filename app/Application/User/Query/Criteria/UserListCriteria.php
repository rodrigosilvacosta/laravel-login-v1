<?php

namespace App\Application\User\Query\Criteria;

use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

class UserListCriteria
{
    use PropertyAccessTrait;

    private function __construct(
        private readonly ?string $firstName,
        private readonly ?string $lastName,
        private readonly ?string $email
    ) {}

    public static function create(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null
    ): self {
        return new self(
            $firstName,
            $lastName,
            $email
        );
    }
}
