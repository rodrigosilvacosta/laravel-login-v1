<?php

namespace App\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property string $value
 */
final class LastName
{
    use PropertyAccessTrait;

    /**
     * ^  inicio da validação
     * [\p{L}] só letras permitidas
     * {2,45} entre 2 e 45 caracteres
     * $ fim da validação
     * u suporte a Unicode (acentos funcionando)
     */
    private const VALIDATE_REGEX = '/^[\p{L}]{2,45}$/u';

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
        if (1 !== preg_match(self::VALIDATE_REGEX, $this->value)) {
            throw new AppDomainException(AppDomainExceptionCodeEnum::LAST_NAME_INVALID);
        }
    }
}
