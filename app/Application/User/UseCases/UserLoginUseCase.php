<?php

namespace App\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserLoginInputDto;
use App\Application\User\Dtos\Outputs\UserLoginOutputDto;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\Repositories\SecurityUserRepositoryInterface;
use App\Domain\Shared\Security\Service\PasswordVerifyServiceInterface;
use App\Domain\Shared\Security\Service\UserAccessTokenServiceInterface;
use App\Domain\Shared\Security\ValueObjects\DeviceName;
use App\Domain\Shared\Security\ValueObjects\PlainPassword;
use App\Domain\Shared\ValueObjects\Email;

class UserLoginUseCase
{
    public function __construct(
        private SecurityUserRepositoryInterface $securityUserRepository,
        private PasswordVerifyServiceInterface $passwordVerifyService,
        private UserAccessTokenServiceInterface $userAccessTokenService
    ) {}

    public function execute(UserLoginInputDto $inputDto): UserLoginOutputDto
    {
        $email = Email::create($inputDto->email);
        $plainPassword = PlainPassword::create($inputDto->password);
        $deviceName = DeviceName::create($inputDto->deviceName);
        $hashedPassword = $this->securityUserRepository->findHashedPasswordByEmail($email);

        if (! $hashedPassword) {
            $this->passwordVerifyService->verifyForTimingAttackMitigation();

            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE);
        }

        if (! $this->passwordVerifyService->verify($plainPassword, $hashedPassword)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE);
        }

        $userUuid = $this->securityUserRepository->findUserUuidByEmail($email);
        $accessToken = $this->userAccessTokenService->generateToken($userUuid, $deviceName);

        return UserLoginOutputDto::createFrom($accessToken->value);
    }
}
