<?php

namespace App\Application\User\UseCases\FindUser;

use App\Application\User\Dtos\Inputs\FindUserByUuidInputDto;
use App\Application\User\Dtos\Outputs\FindUserByUuidOutputDto;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\UserUuid;

class FindUserByUuidUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(FindUserByUuidInputDto $inputDto): FindUserByUuidOutputDto
    {
        $userUuid = UserUuid::fromString($inputDto->uuid);

        $userEntity = $this->userRepository->findByUuid($userUuid);

        if (!$userEntity) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_NOT_FOUND);
        }

        return FindUserByUuidOutputDto::createFrom(
            uuid: $userEntity->uuid->value,
            firstName: $userEntity->firstName->value,
            lastName: $userEntity->lastName->value,
            email: $userEntity->email->value
        );
    }
}
