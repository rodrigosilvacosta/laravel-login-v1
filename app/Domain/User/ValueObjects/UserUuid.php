<?php

namespace App\Domain\User\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;
use Ramsey\Uuid\Uuid;

/**
 * @property string $value
 */
final class UserUuid
{
    use PropertyAccessTrait;
    /**
     * @param string $value
     */
    public function __construct(private readonly string $value)
    {
        $this->validate();
    }

    public static function fromUuid(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    private function validate(): void
    {
        if (!Uuid::isValid($this->value)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::UUID_INVALID);
        }
    }
}
