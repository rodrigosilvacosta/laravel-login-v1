<?php

namespace App\Domain\Shared\Security\Service;

use App\Domain\Shared\Security\ValueObjects\AccessToken;
use App\Domain\Shared\Security\ValueObjects\DeviceName;
use App\Domain\User\ValueObjects\UserUuid;

interface UserAccessTokenServiceInterface
{
    public function generateToken(UserUuid $userUuid, DeviceName $deviceName): AccessToken;
}
