<?php

namespace Tests\Unit\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\ValueObjects\Email;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    #[DataProvider('emails_valid_provider')]
    public function test_email_instantiation(string $emailProvided): void
    {
        $email = new Email($emailProvided);

        $this->assertSame($email->value, $emailProvided);
    }

    #[DataProvider('emails_valid_provider')]
    public function test_email_creation(string $emailProvided): void
    {
        $email = Email::create($emailProvided);

        $this->assertSame($email->value, $emailProvided);
    }

    #[DataProvider('emails_invalid_provider')]
    public function test_email_instatiation_fail(string $emailProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::EMAIL_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::EMAIL_INVALID->value);

        new Email($emailProvided);
    }

    #[DataProvider('emails_invalid_provider')]
    public function test_email_creation_fail(string $emailProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::EMAIL_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::EMAIL_INVALID->value);

        Email::create($emailProvided);
    }

    public static function emails_valid_provider(): array
    {
        return [
            ['usuario@dominio.com'],
            ['nome.sobrenome@empresa.org'],
            ['contato+teste@gmail.com'],
            ['user123@exemplo.net'],
            ['meu-email@sub.dominio.com'],
            ['a@b.co'],
            ['nome_sobrenome@dominio.io'],
            ["usuario@[192.168.0.1]"], //  mas válido pelo RFC
        ];
    }

    public static function emails_invalid_provider(): array
    {
        return [
            [" usuario@dominio.com "], // (espaços nas extremidades)
            [""], // (string vazia)
            [" "], // (apenas espaços)
            ["us\"er@dominio.com"], // aspas no
            ["üser@dominio.com"], // Unicode
        ];
    }
}
