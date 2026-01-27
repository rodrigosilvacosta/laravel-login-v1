<?php

namespace App\Application\User\Repositories\Dtos;

use App\Domain\Shared\Helpers\Traits\PropertyAccessTrait;

/**
 * @property int $total
 * @property int $perPage
 * @property int $currentPage
 * @property int $lastPage
 * @property array $rows
 */
final class PaginatedResultDto
{
    use PropertyAccessTrait;

    private function __construct(
        private readonly int $total,
        private readonly int $perPage,
        private readonly int $currentPage,
        private readonly int $lastPage,
        private readonly array $rows
    ) {}

    public static function create(
        int $total,
        int $perPage,
        int $currentPage,
        int $lastPage,
        array $rows
    ): self {
        return new self($total, $perPage, $currentPage, $lastPage, $rows);
    }
}
