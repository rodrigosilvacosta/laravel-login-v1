<?php

namespace Tests\Unit\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\ValueObjects\LastName;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LastNameTest extends TestCase
{
    public function test_last_name_instantiation(): void
    {
        $lastNameProvided = 'Rodrigo';
        $lastName = new LastName($lastNameProvided);

        $this->assertSame($lastName->value, $lastNameProvided);
    }

    public function test_last_name_creation(): void
    {
        $lastNameProvided = 'Rodrigo';
        $lastName = LastName::create($lastNameProvided);

        $this->assertSame($lastName->value, $lastNameProvided);
    }

    #[DataProvider('last_name_invalid_provider')]
    public function test_last_name_instatiation_fail(string $lastNameProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::LAST_NAME_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::LAST_NAME_INVALID->value);

        new LastName($lastNameProvided);
    }

    public static function last_name_invalid_provider(): array
    {
        return [
            ['R'], // menos de 2 caracteres
            ['Ro Co'], // espaço entre nomes
            ['ThisIsAReallyLongLastNameExceedingTheMaximumLengthAllowed'], // mais de 45 caracteres
            ['John123'], // contém números
            ['Jane-Doe'], // contém hífen
            ['@lice'], // contém caractere especial
            [''], // vazio
            [' '], // espaço em branco,
            ['<script>alert(1)</script>']
        ];
    }
}
