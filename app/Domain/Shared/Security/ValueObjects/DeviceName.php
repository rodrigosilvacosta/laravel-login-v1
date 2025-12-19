<?php

namespace App\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property string $value
 */
final class DeviceName
{
    use PropertyAccessTrait;

    /**
     * REGEX permitindo os seguintes caracteres:
     * a-z letras minúsculas
     * A-Z letras maiúsculas
     * A-Z	letras maiúsculas
     * 0-9	números
     * 	espaço
     * _ underscore
     * \-.	hífen - e ponto .
     */
    private const REGEX_VALIDATION = '/^[a-zA-Z0-9 _\-.]+$/';

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
        $valuetrimmed = trim($this->value);

        if (empty($valuetrimmed) || !preg_match(self::REGEX_VALIDATION, $valuetrimmed)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::DEVICE_NAME_INVALID);
        }
    }
}
