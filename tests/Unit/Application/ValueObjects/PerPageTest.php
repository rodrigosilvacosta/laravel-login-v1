<?php

namespace Tests\Unit\Application\ValueObjects;

use App\Application\ValueObjects\PerPage;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PerPageTest extends TestCase
{
    public function test_per_page_create_valid_per_page(): void
    {
        $perPage = PerPage::create(1);
        $this->assertEquals(1, $perPage->value);

        $perPage = PerPage::create(100);
        $this->assertEquals(100, $perPage->value);

        $perPage = PerPage::create(50);
        $this->assertEquals(50, $perPage->value);
    }

    #[DataProvider('invalid_per_page_provider')]
    public function test_per_page_throw_exception_for_invalid_per_page(int $value): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::INVALID_PER_PAGE->value);

        PerPage::create($value);
    }

    public static function invalid_per_page_provider(): array
    {
        return [
            'less than min' => [0],
            'negative' => [-1],
            'greater than max' => [101],
            'much greater than max' => [1000],
        ];
    }
}
