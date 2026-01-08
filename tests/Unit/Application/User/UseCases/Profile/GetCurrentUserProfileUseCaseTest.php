<?php

namespace Tests\Unit\Application\User\UseCases\Profile;

use App\Application\User\Dtos\Inputs\GetCurrentUserProfileInputDto;
use App\Application\User\Dtos\Outputs\GetCurrentUserProfileOutputDto;
use App\Application\User\UseCases\Profile\GetCurrentUserProfileUseCase;
use PHPUnit\Framework\TestCase;

class GetCurrentUserProfileUseCaseTest extends TestCase
{
    private GetCurrentUserProfileUseCase $getCurrentUserProfileUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getCurrentUserProfileUseCase = new GetCurrentUserProfileUseCase();
    }

    public function test_get_current_user_profile_use_case_success(): void
    {
        $inputDto = new GetCurrentUserProfileInputDto(
            uuid: '123e4567-e89b-12d3-a456-426614174000',
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com'
        );

        $outputDto = $this->getCurrentUserProfileUseCase->execute($inputDto);

        $this->assertInstanceOf(GetCurrentUserProfileOutputDto::class, $outputDto);
        $this->assertSame([
            'uuid' => $inputDto->uuid,
            'first_name' => $inputDto->firstName,
            'last_name' => $inputDto->lastName,
            'email' => $inputDto->email,
        ], $outputDto->toArray());
    }
}
