<?php

namespace Tests\Unit\Domain\Shared\Security\ValueObjects;

use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Domain\Shared\Security\ValueObjects\DeviceName;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DeviceNameTest extends TestCase
{
    public function test_device_name_instantiation(): void
    {
        $deviceNameProvided = 'My Device Name';
        $deviceName = new DeviceName($deviceNameProvided);

        $this->assertSame($deviceName->value, $deviceNameProvided);
    }

    public function test_device_name_creation(): void
    {
        $deviceNameProvided = 'Work Laptop';
        $deviceName = DeviceName::create($deviceNameProvided);

        $this->assertSame($deviceName->value, $deviceNameProvided);
    }

    #[DataProvider('device_name_invalid_provider')]
    public function test_device_name_creation_fail(string $deviceNameProvided): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionMessage(AppDomainExceptionCodeEnum::DEVICE_NAME_INVALID->getMessage());
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::DEVICE_NAME_INVALID->value);

        DeviceName::create($deviceNameProvided);
    }

    public static function device_name_invalid_provider(): array
    {
        return [
            [''], // vazio
            ['  '], // com somente espaços em branco
        ];
    }
}
