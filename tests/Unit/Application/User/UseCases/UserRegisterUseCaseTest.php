<?php

namespace Tests\Unit\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserRegisterInputDto;
use App\Application\User\Dtos\Outputs\UserRegisterOutputDto;
use App\Application\User\UseCases\UserRegisterUseCase;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserRegisterUseCaseTest extends TestCase
{
    private MockObject&UserRepositoryInterface $mockUserRepository;
    private UserRegisterUseCase $userRegisterUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockUserRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userRegisterUseCase = new UserRegisterUseCase($this->mockUserRepository);
    }

    public function test_user_register_use_case_success(): void
    {
        $inputDto = new UserRegisterInputDto(
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com',
            password: 'password123'
        );

        $uuidCallback = null;
        $this->mockUserRepository
            ->expects($this->once())
            ->method('createWithPassword')
            ->willReturnCallback(function (
                UserEntity $userEntity,
                PlainPassword $plainPassword
            ) use (
                &$uuidCallback,
                $inputDto
            ) {
                $uuidCallback = $userEntity->uuid->value;
                $this->assertSame($inputDto->password, $plainPassword->value);
                $this->assertSame($inputDto->firstName, $userEntity->firstName->value);
                $this->assertSame($inputDto->lastName, $userEntity->lastName->value);
                $this->assertSame($inputDto->email, $userEntity->email->value);

                return UserEntity::createFromPrimitives(
                    id: 1,
                    uuid: $userEntity->uuid->value,
                    firstName: $userEntity->firstName->value,
                    lastName: $userEntity->lastName->value,
                    email: $userEntity->email->value
                );
            });

        $outputDto = $this->userRegisterUseCase->execute($inputDto);

        $this->assertInstanceOf(UserRegisterOutputDto::class, $outputDto);
        $this->assertIsString($uuidCallback);
        $this->assertSame(['uuid' => $uuidCallback], $outputDto->toArray());
    }

    public function test_register_user_use_case_when_creation_fails(): void
    {
        $inputDto = new UserRegisterInputDto(
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com',
            password: 'password123'
        );

        $this->mockUserRepository
            ->expects($this->once())
            ->method('createWithPassword')
            ->willReturn(null);

        $this->expectException(AppDomainException::class);
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::USER_CREATE_FAILURE->value);

        $this->userRegisterUseCase->execute($inputDto);
    }
}
