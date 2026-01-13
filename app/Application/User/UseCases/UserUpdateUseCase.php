<?php

namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;

class UserUpdateUseCase
{
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {}

    public function execute(): int
    {
        /**
         * @todo Criar lógica do caso de uso
         */
        return 0;
    }
}
