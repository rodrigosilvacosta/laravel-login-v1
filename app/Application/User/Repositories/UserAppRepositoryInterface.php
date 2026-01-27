<?php

namespace App\Application\User\Repositories;

use App\Application\User\Query\Criteria\UserListCriteria;
use App\Application\User\Repositories\Dtos\PaginatedResultDto;
use App\Application\ValueObjects\PageNumber;
use App\Application\ValueObjects\PerPage;

interface UserAppRepositoryInterface
{
    public function listPaginated(
        UserListCriteria $criteria,
        PageNumber $pageNumber,
        PerPage $perPage
    ): PaginatedResultDto;
}
