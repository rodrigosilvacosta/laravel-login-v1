<?php

namespace Tests\Unit\Application\User\UseCases\FindUser;

use App\Application\User\Dtos\Inputs\FindUserByUuidInputDto;
use App\Application\User\Dtos\Outputs\FindUserByUuidOutputDto;
use App\Application\User\UseCases\FindUser\FindUserByUuidUseCase;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserUuid;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\Shared\ValueObjects\FirstName;
use App\Domain\Shared\ValueObjects\LastName;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindUserByUuidUseCaseTest extends TestCase
{
    private MockObject&UserRepositoryInterface $mockUserRepository;
    private FindUserByUuidUseCase $findUserByUuidUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockUserRepository = $this->createMock(UserRepositoryInterface::class);
        $this->findUserByUuidUseCase = new FindUserByUuidUseCase($this->mockUserRepository);
    }

    public function test_find_user_by_uuid_use_case_success(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $inputDto = new FindUserByUuidInputDto(uuid: $uuidString);
        $userUuid = UserUuid::fromString($uuidString);

        $userEntity = new UserEntity(
            id: 1,
            uuid: $userUuid,
            firstName: FirstName::create('John'),
            lastName: LastName::create('Doe'),
            email: Email::create('john@example.com'),
        );

        $this->mockUserRepository
            ->expects($this->once())
            ->method('findByUuid')
            ->with($userUuid)
            ->willReturn($userEntity);

        $outputDto = $this->findUserByUuidUseCase->execute($inputDto);

        $this->assertInstanceOf(FindUserByUuidOutputDto::class, $outputDto);
        $this->assertSame([
            'uuid' => $uuidString,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ], $outputDto->toArray());
    }

    public function test_find_user_by_uuid_use_case_when_user_not_found(): void
    {
        $uuidString = '123e4567-e89b-12d3-a456-426614174000';
        $inputDto = new FindUserByUuidInputDto(uuid: $uuidString);
        $userUuid = UserUuid::fromString($uuidString);

        $this->mockUserRepository
            ->expects($this->once())
            ->method('findByUuid')
            ->with($userUuid)
            ->willReturn(null);

        $this->expectException(AppDomainException::class);
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::USER_NOT_FOUND->value);

        $this->findUserByUuidUseCase->execute($inputDto);
    }
}
