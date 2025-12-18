<?php

namespace App\Application\User\Dtos\Outputs;

use App\Application\Dto\OutputDto;

class LoginUserOutputDto extends OutputDto
{
    public function __construct(
        public readonly string $token,
    ) {}

    public static function createFrom(
        string $token,
    ): self {
        return new self(
            token: $token,
        );
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
