<?php

namespace App\Application\User\UseCases\RegisterUser;

use App\Application\User\Dtos\Inputs\RegisterUserInputDto;
use App\Application\User\Dtos\Outputs\RegisterUserOutputDto;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(RegisterUserInputDto $inputDto): RegisterUserOutputDto
    {
        $userEntity = UserEntity::register(
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName,
            email: $inputDto->email
        );

        $plainPassword = PlainPassword::create($inputDto->password);
        $userEntity = $this->userRepository->createWithPassword($userEntity, $plainPassword);

        if (!$userEntity) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_CREATE_FAILURE);
        }

        /**
         * @todo enviar email de confirmação
         */

        return RegisterUserOutputDto::createFrom($userEntity->uuid->value);
    }
}
