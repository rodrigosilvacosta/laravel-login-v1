<?php

namespace Tests\Unit\Application\User\UseCases;

use App\Application\User\UseCases\UserUpdateUseCase;
use App\Domain\User\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserUpdateUseCaseTest extends TestCase
{
    private MockObject&UserRepositoryInterface $mockUserRepository;
    private UserUpdateUseCase $userUpdateUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockUserRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userUpdateUseCase = new UserUpdateUseCase($this->mockUserRepository);
    }

    public function test_user_update_successfully(): void
    {
        $this->markTestIncomplete('UserUpdateUseCase is not yet implemented.');
    }
}
