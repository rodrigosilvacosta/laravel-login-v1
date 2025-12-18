<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property string $value
 */
final class Email
{
    use PropertyAccessTrait;

    public function __construct(private readonly string $value)
    {
        self::validate();
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    private function validate(): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::EMAIL_INVALID);
        }
    }
}
