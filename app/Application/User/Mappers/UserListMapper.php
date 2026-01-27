<?php

namespace App\Application\User\Mappers;

use App\Application\Mappers\MapperInterface;

class UserListMapper implements MapperInterface
{
    private function __construct(private array $mappedData) {}

    public function getMappedData(): array
    {
        return $this->mappedData;
    }

    public static function create(
        int $total,
        int $perPage,
        int $currentPage,
        int $lastPage,
        array $rows
    ): self {
        return new self([
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'users' => self::mapUsers($rows),
        ]);
    }

    private static function mapUsers(array $rows): array
    {
        return array_map(
            fn ($row) => [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'uuid' => $row['uuid'],
            ],
            $rows
        );
    }
}
