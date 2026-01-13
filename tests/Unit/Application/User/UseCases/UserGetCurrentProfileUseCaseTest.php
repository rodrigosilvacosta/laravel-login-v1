<?php

namespace Tests\Unit\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserGetCurrentProfileInputDto;
use App\Application\User\Dtos\Outputs\UserGetCurrentProfileOutputDto;
use App\Application\User\UseCases\UserGetCurrentProfileUseCase;
use PHPUnit\Framework\TestCase;

class UserGetCurrentProfileUseCaseTest extends TestCase
{
    private UserGetCurrentProfileUseCase $userGetCurrentProfileUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userGetCurrentProfileUseCase = new UserGetCurrentProfileUseCase();
    }

    public function test_get_current_user_profile_use_case_success(): void
    {
        $inputDto = new UserGetCurrentProfileInputDto(
            uuid: '123e4567-e89b-12d3-a456-426614174000',
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com'
        );

        $outputDto = $this->userGetCurrentProfileUseCase->execute($inputDto);

        $this->assertInstanceOf(UserGetCurrentProfileOutputDto::class, $outputDto);
        $this->assertSame([
            'uuid' => $inputDto->uuid,
            'first_name' => $inputDto->firstName,
            'last_name' => $inputDto->lastName,
            'email' => $inputDto->email,
        ], $outputDto->toArray());
    }
}
