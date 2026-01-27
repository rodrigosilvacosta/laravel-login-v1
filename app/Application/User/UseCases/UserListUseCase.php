<?php

namespace App\Application\User\UseCases;

use App\Application\User\Dtos\Inputs\UserListInputDto;
use App\Application\User\Dtos\Outputs\MappedDataOutputDto;
use App\Application\User\Mappers\UserListMapper;
use App\Application\User\Query\Criteria\UserListCriteria;
use App\Application\User\Repositories\UserAppRepositoryInterface;
use App\Application\ValueObjects\PageNumber;
use App\Application\ValueObjects\PerPage;

class UserListUseCase
{
    public function __construct(private UserAppRepositoryInterface $userAppRepository) {}

    public function execute(UserListInputDto $inputDto): MappedDataOutputDto
    {
        $pageNumber = PageNumber::create($inputDto->page);
        $perPage = PerPage::create($inputDto->perPage);
        $criteria = UserListCriteria::create(
            firstName: $inputDto->firstName,
            lastName: $inputDto->lastName,
            email: $inputDto->email
        );

        $paginatedResultDto = $this->userAppRepository->listPaginated(
            criteria: $criteria,
            pageNumber: $pageNumber,
            perPage: $perPage,
        );

        $mapper = UserListMapper::create(
            total: $paginatedResultDto->total,
            perPage: $paginatedResultDto->perPage,
            currentPage: $paginatedResultDto->currentPage,
            lastPage: $paginatedResultDto->lastPage,
            rows: $paginatedResultDto->rows
        );

        return MappedDataOutputDto::createMappedData($mapper);
    }
}
