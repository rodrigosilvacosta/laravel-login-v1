<?php

namespace App\Providers\Infrastructure;

use App\Domain\Shared\Security\Repositories\SecurityUserRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Security\SecurityUserRepository;
use App\Infrastructure\Persistence\Eloquent\User\UserRespository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(SecurityUserRepositoryInterface::class, SecurityUserRepository::class);
        $this->app->bind(SecurityUserRepositoryInterface::class, SecurityUserRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRespository::class);
    }

    public function boot(): void
    {
        //
    }
}
