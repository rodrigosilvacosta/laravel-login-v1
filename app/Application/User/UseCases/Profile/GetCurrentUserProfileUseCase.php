<?php

namespace App\Application\User\UseCases\Profile;

use App\Application\User\Dtos\Inputs\GetCurrentUserProfileInputDto;
use App\Application\User\Dtos\Outputs\GetCurrentUserProfileOutputDto;

class GetCurrentUserProfileUseCase
{
    public function __construct() {}

    public function execute(GetCurrentUserProfileInputDto $inputDto): GetCurrentUserProfileOutputDto
    {
        return GetCurrentUserProfileOutputDto::createFrom(
            uuid: $inputDto->uuid,
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName,
            email: $inputDto->email
        );
    }
}
