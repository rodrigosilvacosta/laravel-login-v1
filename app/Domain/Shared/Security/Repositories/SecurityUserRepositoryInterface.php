<?php

namespace App\Domain\Shared\Security\Repositories;

use App\Domain\Shared\Security\ValueObjects\HashedPassword;
use App\Domain\Shared\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserUuid;

interface SecurityUserRepositoryInterface
{
    public function findHashedPasswordByEmail(Email $email): ?HashedPassword;
    public function findUserUuidByEmail(Email $email): ?UserUuid;
}
