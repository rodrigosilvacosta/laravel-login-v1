<?php

namespace App\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property string $value
 */
final class PlainPassword
{
    use PropertyAccessTrait;

    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 64;

    public function __construct(private readonly string $value)
    {
        $this->validate();
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    private function validate(): void
    {
        if (
            strlen($this->value) <= self::MIN_LENGTH
            && strlen($this->value) >= self::MAX_LENGTH
        ) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::PASSWORD_INVALID);
        }
    }
}
