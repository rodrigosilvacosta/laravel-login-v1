<?php

namespace App\Domain\User\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface as RamseyUuidInterface;

/**
 * @property RamseyUuidInterface $uuid
 * @property-read string $value
 */
final class UserUuid
{
    use PropertyAccessTrait;

    private string $value;

    private function __construct(private RamseyUuidInterface $uuid)
    {
        $this->value = $this->uuid->toString();
    }

    public static function fromString(string $strValue): self
    {
        if (!RamseyUuid::isValid($strValue)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::UUID_INVALID);
        }

        return new self(RamseyUuid::fromString($strValue));
    }

    public static function generate(): self
    {
        return new self(RamseyUuid::uuid4());
    }
}
