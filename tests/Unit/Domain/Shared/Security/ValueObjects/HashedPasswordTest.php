<?php

namespace Tests\Unit\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\ValueObjects\HashedPassword;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HashedPasswordTest extends TestCase
{
    public function test_hashedd_password_instantiation(): void
    {
        $hashedPasswordProvided = 'somehashedpasswordstring';
        $hashedPassword = new HashedPassword($hashedPasswordProvided);

        $this->assertSame($hashedPassword->value, $hashedPasswordProvided);
    }

    public function test_hashed_password_creation(): void
    {
        $hashedPasswordProvided = 'anotherhashedpasswordstring';
        $hashedPassword = HashedPassword::create($hashedPasswordProvided);

        $this->assertSame($hashedPassword->value, $hashedPasswordProvided);
    }

    #[DataProvider('hashed_password_invalid_provider')]
    public function test_hashed_password_creation_fail(string $hashedPasswordProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::PASSWORD_HASHED_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::PASSWORD_HASHED_INVALID->value);

        HashedPassword::create($hashedPasswordProvided);
    }

    public static function hashed_password_invalid_provider(): array
    {
        return [
            [''], // vazio
            ['   '], // apenas espaços
        ];
    }
}
