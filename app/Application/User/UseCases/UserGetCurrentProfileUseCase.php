<?php

namespace App\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserGetCurrentProfileInputDto;
use App\Application\User\Dtos\Outputs\UserGetCurrentProfileOutputDto;

class UserGetCurrentProfileUseCase
{
    public function __construct() {}

    public function execute(UserGetCurrentProfileInputDto $inputDto): UserGetCurrentProfileOutputDto
    {
        return UserGetCurrentProfileOutputDto::createFrom(
            uuid: $inputDto->uuid,
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName,
            email: $inputDto->email
        );
    }
}
