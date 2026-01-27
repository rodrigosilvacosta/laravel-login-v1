<?php

use App\Http\Controllers\Admin\User\UserRegisterController;
use App\Http\Controllers\Admin\User\UserFindByUuidController;
use App\Http\Controllers\Admin\User\UserLoginController;
use App\Http\Controllers\Admin\User\UserLogoutController;
use App\Http\Controllers\Admin\User\UserGetProfileController;
use App\Http\Controllers\Admin\User\UserUpdateController;
use App\Http\Controllers\Admin\User\UserListController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('.admin')->middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->name('.users')->group(function () {
        Route::post('/', UserRegisterController::class)->name('.create');
        Route::get('/', UserListController::class)->name('.list');
        Route::get('/uuid/{uuid}', UserFindByUuidController::class)->name('.find-by-uuid');
        Route::get('/profile', UserGetProfileController::class)->name('.profile');
        Route::put('/', UserUpdateController::class)->name('.update');
    });
    Route::get('/logout', UserLogoutController::class)->name('.logout');
});
Route::post('/admin/login', UserLoginController::class)->name('.admin.login');
