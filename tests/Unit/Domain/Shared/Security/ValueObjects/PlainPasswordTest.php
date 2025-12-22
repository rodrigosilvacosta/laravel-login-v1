<?php

namespace Tests\Unit\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PlainPasswordTest extends TestCase
{
    public function plain_password_instantiation(): void
    {
        $plainPasswordProvided = 'password';
        $plainPassword = new PlainPassword($plainPasswordProvided);

        $this->assertSame($plainPassword->value, $plainPasswordProvided);
    }

    public function test_plain_password_creation(): void
    {
        $plainPasswordProvided = 'passwordpassword';
        $plainPassword = PlainPassword::create($plainPasswordProvided);

        $this->assertSame($plainPassword->value, $plainPasswordProvided);
    }

    #[DataProvider('plain_password_invalid_provider')]
    public function test_plain_password_creation_fail(string $plainPasswordProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::PASSWORD_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::PASSWORD_INVALID->value);

        PlainPassword::create($plainPasswordProvided);
    }

    public static function plain_password_invalid_provider(): array
    {
        return [
            ['short'], // muito curto
            [str_repeat('a', 65)], // muito longo
            [str_repeat(' ', 10)], // somente espaços em branco
        ];
    }
}
