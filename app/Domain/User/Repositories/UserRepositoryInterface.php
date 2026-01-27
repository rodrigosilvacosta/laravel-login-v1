<?php

namespace App\Domain\User\Repositories;

use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\ValueObjects\UserUuid;

interface UserRepositoryInterface
{
    public function createWithPassword(UserEntity $userEntity, PlainPassword $plainPassword): UserEntity;
    public function findByUuid(UserUuid $uuid): ?UserEntity;
    public function update(UserEntity $userEntity): int;
    public function existsByEmail(Email $email): bool;
}
