<?php

namespace App\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserFindByUuidInputDto;
use App\Application\User\Dtos\Outputs\UserFindByUuidOutputDto;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserUuid;

class UserFindByUuidUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(UserFindByUuidInputDto $inputDto): UserFindByUuidOutputDto
    {
        $userUuid = UserUuid::fromString($inputDto->uuid);

        $userEntity = $this->userRepository->findByUuid($userUuid);

        if (!$userEntity) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_NOT_FOUND);
        }

        return UserFindByUuidOutputDto::createFrom(
            uuid: $userEntity->uuid->value,
            firstName: $userEntity->firstName->value,
            lastName: $userEntity->lastName->value,
            email: $userEntity->email->value
        );
    }
}
