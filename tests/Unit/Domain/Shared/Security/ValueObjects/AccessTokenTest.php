<?php

namespace Tests\Unit\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\ValueObjects\AccessToken;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    public function test_access_token_instantiation(): void
    {
        $accessTokenProvided = 'some-valid-access-token-string';
        $accessToken = new AccessToken($accessTokenProvided);

        $this->assertSame($accessToken->value, $accessTokenProvided);
    }

    public function test_access_token_creation(): void
    {
        $accessTokenProvided = 'some-valid-access-token-string';
        $accessToken = AccessToken::create($accessTokenProvided);

        $this->assertSame($accessToken->value, $accessTokenProvided);
    }

    #[DataProvider('access_tokens_valid_provider')]
    public function test_access_token_fail(string $accessTokenProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::ACCESS_TOKEN_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::ACCESS_TOKEN_INVALID->value);

        AccessToken::create($accessTokenProvided);
    }

    public static function access_tokens_valid_provider(): array
    {
        return [
            ['  '], // com somente espaços em branco
            [''], // vazia
        ];
    }
}
