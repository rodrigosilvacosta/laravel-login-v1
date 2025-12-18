<?php

namespace App\Infrastructure\Persistence\Eloquent\Security;

use App\Domain\Shared\Security\Repositories\SecurityUserRepositoryInterface;
use App\Domain\Shared\Security\ValueObjects\HashedPassword;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserUuid;
use App\Models\User;

class SecurityUserRepository implements SecurityUserRepositoryInterface
{
    public function findHashedPasswordByEmail(Email $email): ?HashedPassword
    {
        $model = User::select('password')->where('email', $email->value)->first();

        if (! $model) {
            return null;
        }

        return HashedPassword::create($model->password);
    }

    public function findUserUuidByEmail(Email $email): ?UserUuid
    {
        $model = User::select('uuid')->where('email', $email->value)->first();

        if (! $model) {
            return null;
        }

        return UserUuid::fromUuid($model->uuid);
    }
}
