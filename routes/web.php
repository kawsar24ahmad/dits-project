<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\ServiceController as CustomerServiceController;



use App\Http\Controllers\SslCommerzPaymentController;

Route::get('/', function () {
    return view('welcome');
});


// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);

Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END




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
        Route::resource('orders', OrderController::class)->names('admin.orders');
    });


});

Route::middleware([ 'auth','role:customer'])->group(function ()  {
    Route::prefix('customer')->group(function ()  {
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
        Route::patch('/profile/changePhoto', [CustomerProfileController::class, 'changePhoto'])->name('customer.profile.changePhoto');
        Route::delete('/profile', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');

        Route::get('/services', [CustomerServiceController::class, 'index'])->name('customer.services.index');
        Route::get('/services/all', [CustomerServiceController::class, 'showAll'])->name('customer.services.all');

    });

});

require __DIR__.'/auth.php';
