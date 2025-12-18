<?php

namespace App\Infrastructure\Service\Security\AccessToken;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\Service\UserAccessTokenServiceInterface;
use App\Domain\Shared\Security\ValueObjects\AccessToken;
use App\Domain\Shared\Security\ValueObjects\DeviceName;
use App\Domain\User\ValueObjects\UserUuid;
use App\Models\User;

class LaravelUserAccessTokenService implements UserAccessTokenServiceInterface
{
    public function generateToken(UserUuid $userUuid, DeviceName $deviceName): AccessToken
    {
        $accessToken = $this->createUserAccessToken($userUuid, $deviceName);

        if (!$accessToken) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_ACCESS_TOKEN_CREATION_FAILURE);
        }

        return AccessToken::create($accessToken);
    }

    private function createUserAccessToken(UserUuid $userUuid, DeviceName $deviceName): ?string
    {
        $model = User::where('uuid', $userUuid->value)->first();

        if (! $model) {
            return null;
        }

        return $model->createToken($deviceName->value, ['*'])->plainTextToken;
    }
}
