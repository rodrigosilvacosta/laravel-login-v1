<?php

namespace App\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property string $value
 */
final class AccessToken
{
    use PropertyAccessTrait;

    private function __construct(private readonly string $value)
    {
        $this->validate();
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    private function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::ACCESS_TOKEN_INVALID);
        }
    }
}
