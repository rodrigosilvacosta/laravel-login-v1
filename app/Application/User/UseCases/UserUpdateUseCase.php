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

        if (null === $userEntity) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_UPDATE_USER_NOT_FOUND);
        }

        $userEntityUpdated = $userEntity->updatePersonalInfo(
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName
        );

        if (
            $userEntity != $userEntityUpdated
            && !$this->userRepository->update($userEntityUpdated)
        ) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_UPDATE_FAILURE);
        }

        return UserUpdateOutputDto::createFrom(
            uuid: $userEntityUpdated->uuid->value,
            firstName: $userEntityUpdated->firstName->value,
            lastName: $userEntityUpdated->lastName->value,
        );
    }
}
