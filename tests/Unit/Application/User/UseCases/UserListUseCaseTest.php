<?php

namespace Tests\Unit\Application\User\UseCases;

use App\Application\User\Query\Criteria\UserListCriteria;
use App\Application\User\Dtos\Inputs\UserListInputDto;
use App\Application\User\Dtos\Outputs\MappedDataOutputDto;
use App\Application\User\Mappers\UserListMapper;
use App\Application\User\Repositories\Dtos\PaginatedResultDto;
use App\Application\User\Repositories\UserAppRepositoryInterface;
use App\Application\User\UseCases\UserListUseCase;
use App\Application\ValueObjects\PageNumber;
use App\Application\ValueObjects\PerPage;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UserListUseCaseTest extends TestCase
{
    private UserAppRepositoryInterface&MockObject $mockUserAppRepository;
    private UserListUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockUserAppRepository = $this->createMock(UserAppRepositoryInterface::class);
        $this->useCase = new UserListUseCase($this->mockUserAppRepository);
    }

    public function test_user_list_use_case_execute_return_users_list(): void
    {
        $inputDto = new UserListInputDto(
            page: 1,
            perPage: 10,
            firstName: 'John',
            lastName: 'Doe',
            email: 'john@example.com'
        );

        $paginatedResultDto = PaginatedResultDto::create(
            total: 1,
            perPage: 10,
            currentPage: 1,
            lastPage: 1,
            rows: []
        );

        $this->mockUserAppRepository
            ->expects($this->once())
            ->method('listPaginated')
            ->with(
                $this->callback(function (UserListCriteria $criteria) use ($inputDto) {
                    return $criteria->firstName === $inputDto->firstName
                        && $criteria->lastName === $inputDto->lastName
                        && $criteria->email === $inputDto->email;
                }),
                $this->callback(function (PageNumber $pageNumber) use ($inputDto) {
                    return $pageNumber->value === $inputDto->page;
                }),
                $this->callback(function (PerPage $perPage) use ($inputDto) {
                    return $perPage->value === $inputDto->perPage;
                })
            )
            ->willReturn($paginatedResultDto);

        $mapped = UserListMapper::create(
            total: $paginatedResultDto->total,
            perPage: $paginatedResultDto->perPage,
            currentPage: $paginatedResultDto->currentPage,
            lastPage: $paginatedResultDto->lastPage,
            rows: $paginatedResultDto->rows
        )->getMappedData();

        $result = $this->useCase->execute($inputDto);

        $this->assertInstanceOf(MappedDataOutputDto::class, $result);
        $this->assertEquals($mapped, $result->toArray());
    }
}
