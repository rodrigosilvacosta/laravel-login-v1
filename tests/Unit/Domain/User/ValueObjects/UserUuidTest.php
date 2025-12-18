<?php

namespace Tests\Unit\Domain\User\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\ValueObjects\UserUuid;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserUuidTest extends TestCase
{
    public function test_user_uuid_instatiation(): void
    {
        $strUuid = '123e4567-e89b-12d3-a456-426614174000';
        $userUuid = new UserUuid($strUuid);

        $this->assertEquals($strUuid, $userUuid->value);
    }

    public function test_user_uuid_from_valid_uuid(): void
    {
        $strUuid = '123e4567-e89b-12d3-a456-426614174000';
        $userUuid = UserUuid::fromUuid($strUuid);

        $this->assertEquals($strUuid, $userUuid->value);
    }

    public function test_user_uuid_method_generate(): void
    {
        $userUuid = UserUuid::generate();

        $this->assertTrue(Uuid::isValid($userUuid->value));
    }

    #[DataProvider('invalid_uuid_provider')]
    public function test_user_uuid_from_invalid_uuid(string $strUuid): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::UUID_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::UUID_INVALID->value);

        UserUuid::fromUuid($strUuid);
    }

    public static function invalid_uuid_provider(): array
    {
        return [
            ['invalid-uuid-string'],
            ['123e4567-e89b-12d3-a456-42661417400Z'], // Invalid character
            ['123e4567e89b12d3a456426614174000'],     // Missing hyphens
            ['123e4567-e89b-12d3-a456-42661417400'],  // Too short
            ['123e4567-e89b-12d3-a456-4266141740000'], // Too long
            [''],                                      // Empty string
        ];
    }
}
