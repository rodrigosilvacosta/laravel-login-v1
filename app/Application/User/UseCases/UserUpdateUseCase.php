<?php

namespace App\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserUpdateInputDto;
use App\Application\User\Dtos\Outputs\UserUpdateOutputDto;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserUuid;

class UserUpdateUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserUpdateInputDto $inputDto): UserUpdateOutputDto
    {
        $userUuid = UserUuid::fromString($inputDto->uuid);
        $userEntity = $this->userRepository->findByUuid($userUuid);
        $userEntity = $userEntity->updatePersonalInfo(
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName
        );

        if (0 === $this->userRepository->update($userEntity)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_UPDATE_FAILURE);
        }

        return UserUpdateOutputDto::createFrom(
            uuid: $userEntity->uuid->value,
            firstName: $userEntity->firstName->value,
            lastName: $userEntity->lastName->value,
        );
    }
}
