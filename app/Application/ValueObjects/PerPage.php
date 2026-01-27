<?php

namespace App\Application\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property int $value
 */
final class PerPage
{
    use PropertyAccessTrait;

    private const MIN = 1;
    private const MAX = 100;

    private function __construct(
        private readonly int $value,
    ) {}

    public static function create(int $value): self
    {
        self::validate($value);

        return new self($value);
    }

    private static function validate(int $value): void
    {
        if ($value < self::MIN || $value > self::MAX) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::INVALID_PER_PAGE);
        }
    }
}
