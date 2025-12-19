<?php

namespace Tests\Unit\Domain\Shared\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\ValueObjects\FirstName;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FirstNameTest extends TestCase
{
    public function test_first_name_instantiation(): void
    {
        $firstNameProvided = 'Rodrigo';
        $firstName = new FirstName($firstNameProvided);

        $this->assertSame($firstName->value, $firstNameProvided);
    }

    public function test_first_name_creation(): void
    {
        $firstNameProvided = 'Rodrigo';
        $firstName = FirstName::create($firstNameProvided);

        $this->assertSame($firstName->value, $firstNameProvided);
    }

    #[DataProvider('first_name_invalid_provider')]
    public function test_first_name_instatiation_fail(string $firstNameProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::FIRST_NAME_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::FIRST_NAME_INVALID->value);

        new FirstName($firstNameProvided);
    }

    public static function first_name_invalid_provider(): array
    {
        return [
            ['R'], // menos de 2 caracteres
            ['Ro Co'], // espaço entre nomes
            ['ThisIsAReallyLongFirstNameExceedingTheMaximumLengthAllowed'], // mais de 45 caracteres
            ['John123'], // contém números
            ['Jane-Doe'], // contém hífen
            ['@lice'], // contém caractere especial
            [''], // vazio
            [' '], // espaço em branco,
            ['<script>alert(1)</script>']
        ];
    }
}
