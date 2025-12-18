<?php

namespace App\Domain\Shared\Exceptions;

enum AppDomainExceptionCodeEnum: int
{
    case EMAIL_INVALID = 1000;
    case PASSWORD_INVALID = 1100;
    case PASSWORD_HASHED_INVALID = 1101;
    case DEVICE_NAME_INVALID = 1102;
    case ACCESS_TOKEN_INVALID = 1103;
    case UUID_INVALID = 1200;
    case NAME_INVALID = 1300;
    case USER_NOT_FOUND = 1400;
    case USER_AUTHENTICATION_FAILURE = 1500;
    case USER_ACCESS_TOKEN_CREATION_FAILURE = 1501;
    case USER_CREATE_FAILURE = 1600;


    public function getMessage(): string
    {
        return match ($this) {
            self::EMAIL_INVALID => 'E-mail ínvalido.',
            self::PASSWORD_INVALID => 'Senha ínvalida.',
            self::PASSWORD_HASHED_INVALID => 'Senha ínvalida.',
            self::DEVICE_NAME_INVALID => 'Nome do dispositivo ínvalido.',
            self::ACCESS_TOKEN_INVALID => 'Token de acesso ínvalido.',
            self::UUID_INVALID => 'Uiid ínvalido.',
            self::NAME_INVALID => 'Nome ínvalido.',
            self::USER_NOT_FOUND => 'Usuário não encontrado.',
            self::USER_AUTHENTICATION_FAILURE => 'Usuário ou senha inválidos.',
            self::USER_ACCESS_TOKEN_CREATION_FAILURE => 'Falha ao criar token de acesso.',
            self::USER_CREATE_FAILURE => 'Erro ao tentar cadastrar o usuário.',
        };
    }
}
