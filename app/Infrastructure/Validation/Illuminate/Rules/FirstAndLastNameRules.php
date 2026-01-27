<?php

namespace App\Infrastructure\Validation\Illuminate\Rules;

class FirstAndLastNameRules
{
    /**
     * ^  inicio da validação
     * [\p{L}] só letras permitidas
     * {2,45} entre 2 e 45 caracteres
     * $ fim da validação
     * u suporte a Unicode (acentos funcionando)
     */
    private const VALIDATE_REGEX_FIRST_AND_LAST_NAME = '/^[\p{L}]{2,45}$/u';

    public static function rulesForRequired(): array
    {
        return [
            'required',
            'string',
            'min:2',
            'max:45',
            'regex:'.self::VALIDATE_REGEX_FIRST_AND_LAST_NAME,
        ];
    }

    public static function rulesForOptionalFilter(): array
    {
        return [
            'nullable',
            'string',
            'min:1',
            'max:45',
            'regex:'.self::VALIDATE_REGEX_FIRST_AND_LAST_NAME,
        ];
    }
}
