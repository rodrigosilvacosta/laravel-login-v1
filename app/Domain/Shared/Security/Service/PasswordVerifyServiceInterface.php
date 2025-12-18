<?php

namespace App\Domain\Shared\Security\Service;

use App\Domain\Shared\Security\ValueObjects\HashedPassword;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;

interface PasswordVerifyServiceInterface
{
    public function verify(PlainPassword $plainPassword, HashedPassword $hashedPassword): bool;

    public function verifyForTimingAttackMitigation(): void;
}
