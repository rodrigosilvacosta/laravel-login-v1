<?php

use App\Http\Controllers\Admin\User\UserCreateController;
use App\Http\Controllers\Admin\User\UserFindByUuidController;
use App\Http\Controllers\Admin\User\UserLoginController;
use App\Http\Controllers\Admin\User\UserLogoutController;
use App\Http\Controllers\Admin\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('.admin')->middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->name('.users')->group(function () {
        Route::post('/', UserCreateController::class)->name('.create');
        Route::get('/{uuid}', UserFindByUuidController::class)->name('.findByUuid');
        Route::get('/profile', UserProfileController::class)->name('.profile');
    });
    Route::get('/logout', UserLogoutController::class)->name('.logout');
});
Route::post('/admin/login', UserLoginController::class)->name('.admin.login');
