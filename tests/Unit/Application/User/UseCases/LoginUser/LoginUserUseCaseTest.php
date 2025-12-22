<?php

namespace Tests\Unit\Application\User\UseCases\LoginUser;

use App\Application\User\Dtos\Inputs\LoginUserInputDto;
use App\Application\User\Dtos\Outputs\LoginUserOutputDto;
use App\Application\User\UseCases\LoginUser\LoginUserUseCase;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\Repositories\SecurityUserRepositoryInterface;
use App\Domain\Shared\Security\Service\PasswordVerifyServiceInterface;
use App\Domain\Shared\Security\Service\UserAccessTokenServiceInterface;
use App\Domain\Shared\Security\ValueObjects\AccessToken;
use App\Domain\Shared\Security\ValueObjects\DeviceName;
use App\Domain\Shared\Security\ValueObjects\HashedPassword;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserUuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoginUserUseCaseTest extends TestCase
{
    private MockObject&SecurityUserRepositoryInterface $mockSecurityUserRepository;
    private MockObject&PasswordVerifyServiceInterface $mockPasswordVerifyService;
    private MockObject&UserAccessTokenServiceInterface $mockUserAccessTokenService;
    private LoginUserUseCase $loginUserUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockSecurityUserRepository = $this->createMock(SecurityUserRepositoryInterface::class);
        $this->mockPasswordVerifyService = $this->createMock(PasswordVerifyServiceInterface::class);
        $this->mockUserAccessTokenService = $this->createMock(UserAccessTokenServiceInterface::class);

        $this->loginUserUseCase = new LoginUserUseCase(
            $this->mockSecurityUserRepository,
            $this->mockPasswordVerifyService,
            $this->mockUserAccessTokenService
        );
    }

    public function test_login_user_use_case(): void
    {
        $emailInput = 'test@example.com';
        $passwordInput = 'password123';
        $deviceNameInput = 'Test Device';

        $inputDto = new LoginUserInputDto(
            email: $emailInput,
            password: $passwordInput,
            deviceName: $deviceNameInput
        );

        $email = Email::create($emailInput);
        $plainPassword = PlainPassword::create($passwordInput);
        $deviceName = DeviceName::create($deviceNameInput);
        $hashedPassword = HashedPassword::create('hashed_password');

        $this->mockSecurityUserRepository
            ->expects($this->once())
            ->method('findHashedPasswordByEmail')
            ->with($email)
            ->willReturn($hashedPassword);

        $this->mockPasswordVerifyService
            ->expects($this->never())
            ->method('verifyForTimingAttackMitigation');

        $this->mockPasswordVerifyService
            ->expects($this->once())
            ->method('verify')
            ->with($plainPassword, $hashedPassword)
            ->willReturn(true);

        $userUuid = UserUuid::fromUuid('123e4567-e89b-12d3-a456-426614174000');
        $this->mockSecurityUserRepository
            ->expects($this->once())
            ->method('findUserUuidByEmail')
            ->with($email)
            ->willReturn($userUuid);

        $accessToken = AccessToken::create('access_token_value');
        $this->mockUserAccessTokenService
            ->expects($this->once())
            ->method('generateToken')
            ->with($userUuid, $deviceName)
            ->willReturn($accessToken);

        $outputDto = $this->loginUserUseCase->execute($inputDto);

        $this->assertInstanceOf(LoginUserOutputDto::class, $outputDto);
        $this->assertSame(['token' => $accessToken->value], $outputDto->toArray());
    }

    public function test_login_user_use_case_user_not_found(): void
    {
        $emailInput = 'test@example.com';
        $passwordInput = 'password123';
        $deviceNameInput = 'Test Device';

        $inputDto = new LoginUserInputDto(
            email: $emailInput,
            password: $passwordInput,
            deviceName: $deviceNameInput
        );

        $email = Email::create($emailInput);
        $hashedPassword = null;

        $this->mockSecurityUserRepository
            ->expects($this->once())
            ->method('findHashedPasswordByEmail')
            ->with($email)
            ->willReturn($hashedPassword);

        $this->mockPasswordVerifyService
            ->expects($this->once())
            ->method('verifyForTimingAttackMitigation');

        $this->mockPasswordVerifyService
            ->expects($this->never())
            ->method('verify');

        $this->mockSecurityUserRepository
            ->expects($this->never())
            ->method('findUserUuidByEmail');

        $this->mockUserAccessTokenService
            ->expects($this->never())
            ->method('generateToken');

        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE->value);

        $this->loginUserUseCase->execute($inputDto);
    }

    public function test_login_user_use_case_wrong_password(): void
    {
        $emailInput = 'test@example.com';
        $passwordInput = 'password123';
        $deviceNameInput = 'Test Device';

        $inputDto = new LoginUserInputDto(
            email: $emailInput,
            password: $passwordInput,
            deviceName: $deviceNameInput
        );

        $email = Email::create($emailInput);
        $plainPassword = PlainPassword::create($passwordInput);
        $hashedPassword = HashedPassword::create('hashed_password');

        $this->mockSecurityUserRepository
            ->expects($this->once())
            ->method('findHashedPasswordByEmail')
            ->with($email)
            ->willReturn($hashedPassword);

        $this->mockPasswordVerifyService
            ->expects($this->never())
            ->method('verifyForTimingAttackMitigation');

        $this->mockPasswordVerifyService
            ->expects($this->once())
            ->method('verify')
            ->with($plainPassword, $hashedPassword)
            ->willReturn(false);

        $this->mockSecurityUserRepository
            ->expects($this->never())
            ->method('findUserUuidByEmail');

        $this->mockUserAccessTokenService
            ->expects($this->never())
            ->method('generateToken');

        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE->value);

        $this->loginUserUseCase->execute($inputDto);
    }
}
