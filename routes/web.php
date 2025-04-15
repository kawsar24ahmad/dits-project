<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function ()  {
    Route::prefix('admin')->group(function ()  {
        Route::resource('admin_users', AdminUserController::class);
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
        Route::patch('/profile/changePhoto', [AdminProfileController::class, 'changePhoto'])->name('admin.profile.changePhoto');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
        Route::resource('admin_categories',CategoryController::class);
        Route::resource('services', ServiceController::class)->names('admin.services');
    });

    Route::middleware([ 'auth','role:customer'])->group(function ()  {
        Route::prefix('customer')->group(function ()  {
            Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
            Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
            Route::patch('/profile/changePhoto', [CustomerProfileController::class, 'changePhoto'])->name('customer.profile.changePhoto');
            Route::delete('/profile', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');
        });

    });
});

require __DIR__.'/auth.php';
