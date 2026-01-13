<?php

namespace Tests\Unit\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserUpdateInputDto;
use App\Application\User\Dtos\Outputs\UserUpdateOutputDto;
use App\Application\User\UseCases\UserUpdateUseCase;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserUuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserUpdateUseCaseTest extends TestCase
{
    private MockObject&UserRepositoryInterface $mockUserRepository;
    private UserUpdateUseCase $userUpdateUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockUserRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userUpdateUseCase = new UserUpdateUseCase($this->mockUserRepository);
    }

    public function test_user_update_use_caseSuccess(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@example.com';

        $newFirstName = 'Jane';
        $newLastName = 'Smith';

        $inputDto = new UserUpdateInputDto(
            firstName: $newFirstName,
            lastName: $newLastName,
            uuid: $uuid
        );

        $userEntity = UserEntity::createFromPrimitives(
            id: 1,
            uuid: $uuid,
            firstName: $firstName,
            lastName: $lastName,
            email: $email
        );

        $this->mockUserRepository
            ->expects($this->once())
            ->method('findByUuid')
            ->with($this->callback(fn(UserUuid $u) => $u->value === $uuid))
            ->willReturn($userEntity);

        $this->mockUserRepository
            ->expects($this->once())
            ->method('update')
            ->with($this->callback(function (UserEntity $entity) use ($newFirstName, $newLastName, $uuid) {
                return $entity->firstName->value === $newFirstName
                    && $entity->lastName->value === $newLastName
                    && $entity->uuid->value === $uuid;
            }))
            ->willReturn(1);

        $outputDto = $this->userUpdateUseCase->execute($inputDto);

        $this->assertInstanceOf(UserUpdateOutputDto::class, $outputDto);
        $this->assertSame([
            'uuid' => $uuid,
            'first_name' => $newFirstName,
            'last_name' => $newLastName
        ], $outputDto->toArray());
    }

    public function test_user_update_use_case_update_failure(): void
    {
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@example.com';

        $inputDto = new UserUpdateInputDto(
            firstName: 'Jane',
            lastName: 'Smith',
            uuid: $uuid
        );

        $userEntity = UserEntity::createFromPrimitives(
            id: 1,
            uuid: $uuid,
            firstName: $firstName,
            lastName: $lastName,
            email: $email
        );

        $this->mockUserRepository
            ->expects($this->once())
            ->method('findByUuid')
            ->willReturn($userEntity);

        $this->mockUserRepository
            ->expects($this->once())
            ->method('update')
            ->willReturn(0);

        $this->expectException(AppDomainException::class);
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::USER_UPDATE_FAILURE->value);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::USER_UPDATE_FAILURE->getMessage());

        $this->userUpdateUseCase->execute($inputDto);
    }
}
