<?php

namespace App\Providers\Infrastructure;

use App\Domain\Shared\Security\Service\PasswordVerifyServiceInterface;
use App\Domain\Shared\Security\Service\UserAccessTokenServiceInterface;
use App\Infrastructure\Service\Security\AccessToken\LaravelUserAccessTokenService;
use App\Infrastructure\Service\Security\Password\Illuminate\IlluminatePasswordVerifyService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PasswordVerifyServiceInterface::class, IlluminatePasswordVerifyService::class);
        $this->app->bind(UserAccessTokenServiceInterface::class, LaravelUserAccessTokenService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
