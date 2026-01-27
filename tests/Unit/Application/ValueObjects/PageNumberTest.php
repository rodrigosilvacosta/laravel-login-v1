<?php

namespace Tests\Unit\Application\ValueObjects;

use App\Application\ValueObjects\PageNumber;
use App\Domain\Shared\Exceptions\AppDomainException;
use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageNumberTest extends TestCase
{
    public function test_page_number_create_valid_page_number(): void
    {
        $pageNumber = PageNumber::create(1);
        $this->assertEquals(1, $pageNumber->value);

        $pageNumber = PageNumber::create(100);
        $this->assertEquals(100, $pageNumber->value);

        $pageNumber = PageNumber::create(50);
        $this->assertEquals(50, $pageNumber->value);
    }

    #[DataProvider('invalid_page_numbers_provider')]
    public function test_page_number_throw_exception_for_invalid_page_number(int $value): void
    {
        $this->expectException(AppDomainException::class);
        $this->expectExceptionCode(AppDomainExceptionCodeEnum::INVALID_PAGE_NUMBER->value);

        PageNumber::create($value);
    }

    public static function invalid_page_numbers_provider(): array
    {
        return [
            'less than min' => [0],
            'negative' => [-1],
            'greater than max' => [101],
            'much greater than max' => [1000],
        ];
    }
}
