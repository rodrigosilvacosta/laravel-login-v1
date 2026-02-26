<?php

namespace App\Infrastructure\Persistence\Eloquent\Application\User;

use App\Application\User\Query\Criteria\UserListCriteria;
use App\Application\User\Repositories\Dtos\PaginatedResultDto;
use App\Application\User\Repositories\UserAppRepositoryInterface;
use App\Application\ValueObjects\PageNumber;
use App\Application\ValueObjects\PerPage;
use App\Models\User;

class UserAppRepository implements UserAppRepositoryInterface
{
    public function listPaginated(
        UserListCriteria $criteria,
        PageNumber $pageNumber,
        PerPage $perPage
    ): PaginatedResultDto {
        $collection = User::select([
            'uuid',
            'first_name',
            'last_name',
            'email',
        ])->when(
            $criteria->firstName,
            fn($q) =>
            $q->whereLike('first_name', "%{$criteria->firstName}%")
        )->when(
            $criteria->lastName,
            fn($q) =>
            $q->whereLike('last_name', "%{$criteria->lastName}%")
        )->when(
            $criteria->email,
            fn($q) =>
            $q->whereLike('email', "%{$criteria->email}%")
        )->paginate(
            $perPage->value,
            ['*'],
            'page',
            $pageNumber->value
        );

        return PaginatedResultDto::create(
            total: $collection->total(),
            perPage: $perPage->value,
            currentPage: $pageNumber->value,
            lastPage: $collection->lastPage(),
            rows: $collection->items()
        );
    }
}
