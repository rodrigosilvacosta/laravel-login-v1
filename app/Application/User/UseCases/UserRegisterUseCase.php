<?php

namespace App\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserRegisterInputDto;
use App\Application\User\Dtos\Outputs\UserRegisterOutputDto;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\Shared\ValueObjects\Email;

class UserRegisterUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserRegisterInputDto $inputDto): UserRegisterOutputDto
    {
        $userEntity = UserEntity::createFromBasicPrimitives(
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName,
            email: $inputDto->email
        );

        $email = Email::create($inputDto->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw new AppDomainException(
                AppDomainExceptionCodeEnum::USER_CREATE_EMAIL_ALREADY_EXISTS
            );
        }

        /**
         * @todo a senha deverá ser criada através do link de confirmação
         *  que será enviado por e-mail
         */
        $plainPassword = PlainPassword::create($inputDto->password);
        try {
            $userEntity = $this->userRepository->createWithPassword($userEntity, $plainPassword);
        } catch (\Exception $e) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_CREATE_FAILURE);
        }

        /**
         * @todo enviar email de confirmação
         */

        return UserRegisterOutputDto::createFrom($userEntity->uuid->value);
    }
}
