<?php

namespace App\Infrastructure\Persistence\Eloquent\User;

use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserUuid;
use App\Models\User;


class UserRepository implements UserRepositoryInterface
{
    public function createWithPassword(UserEntity $userEntity, PlainPassword $plainPassword): UserEntity
    {
        $data = [
            'uuid' => $userEntity->uuid->value,
            'first_name' => $userEntity->firstName->value,
            'last_name' => $userEntity->lastName->value,
            'email' => $userEntity->email->value,
            'password' => $plainPassword->value,
        ];

        $model = User::create($data);

        return UserEntity::createFromPrimitives(
            id: $model->id,
            uuid: $model->uuid,
            firstName: $model->first_name,
            lastName: $model->last_name,
            email: $model->email
        );
    }

    public function findByUuid(UserUuid $uuid): ?UserEntity
    {
        $model = User::where('uuid', $uuid->value)->first();

        if (!$model) {
            return null;
        }

        return UserEntity::createFromPrimitives(
            id: $model->id,
            uuid: $model->uuid,
            firstName: $model->first_name,
            lastName: $model->last_name,
            email: $model->email
        );
    }

    public function update(UserEntity $userEntity): int
    {
        $affectedRows = User::where('uuid', $userEntity->uuid->value)->update([
            'first_name' => $userEntity->firstName->value,
            'last_name' => $userEntity->lastName->value
        ]);

        return $affectedRows;
    }
}
