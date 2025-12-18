<?php

namespace App\Infrastructure\Service\Security\Password\Illuminate;

use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\Shared\Security\Service\PasswordVerifyServiceInterface;
use App\Domain\Shared\Security\ValueObjects\HashedPassword;
use Illuminate\Support\Facades\Hash;

class IlluminatePasswordVerifyService implements PasswordVerifyServiceInterface
{
    private const FAKE_HASH = '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
    private const FAKE_PASSWORD = 'fake';

    public function verify(PlainPassword $plainPassword, HashedPassword $hashedPassword): bool
    {
        return Hash::check($plainPassword->value, $hashedPassword->value);
    }

    public function VerifyForTimingAttackMitigation(): void
    {
        Hash::check(self::FAKE_PASSWORD, self::FAKE_HASH);
    }
}
