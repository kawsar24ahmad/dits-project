<?php

use App\Models\FacebookPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ServicePurchaseController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\Admin\FacebookPageController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\WalletController as UserWalletController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\ServiceController as UserServiceController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\User\WalletTransactionController as UserWalletTransactionController;


    Route::get('/insights/{id}', function () {
        $id = request('id');

        $page = FacebookPage::find($id);
        if (!$page) {
            return redirect()->back()->with('error', 'Page not found.');
        }

        $pageAccessToken = $page->page_access_token;
        $pageId = $page->page_id;

        // Step 1: Get posts (basic data only)
        $response = Http::withToken($pageAccessToken)
            ->get("https://graph.facebook.com/{$pageId}/posts", [
                'fields' => 'id,message,created_time',
                'limit' => 5,
            ]);

        $posts = $response->json()['data'] ?? [];

        $results = [];

        // Step 2: Loop and fetch insights, likes, comments, shares per post
        foreach ($posts as $post) {
            $postId = $post['id'];

            // Fetch insights
            $insightResponse = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/{$postId}/insights", [
                    'metric' => 'post_impressions,post_engaged_users'
                ]);

            $insightData = $insightResponse->json()['data'] ?? [];

            $reach = 0;
            $engaged = 0;
            foreach ($insightData as $item) {
                if ($item['name'] === 'post_impressions') {
                    $reach = $item['values'][0]['value'] ?? 0;
                }
                if ($item['name'] === 'post_engaged_users') {
                    $engaged = $item['values'][0]['value'] ?? 0;
                }
            }

            // Fetch likes/comments/shares
            $metaResponse = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/{$postId}", [
                    'fields' => 'likes.summary(true),comments.summary(true),shares',
                ]);

            $meta = $metaResponse->json();

            $results[] = [
                'id' => $postId,
                'message' => $post['message'] ?? '',
                'created_time' => $post['created_time'],
                'likes' => $meta['likes']['summary']['total_count'] ?? 0,
                'comments' => $meta['comments']['summary']['total_count'] ?? 0,
                'shares' => $meta['shares']['count'] ?? 0,
                'reach' => $reach,
                'engagement' => $engaged,
            ];
        }

        return view('facebook.index', compact('results'));
    })->middleware(['auth', 'role:user,customer'])->name('facebook.insights');


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

        Route::get('/wallet-transactions', [WalletTransactionController::class, 'index'])->name('admin.wallet.transactions');
        Route::post('/wallet-transactions/{transaction}', [WalletTransactionController::class, 'update'])->name('admin.wallet.transactions.update');
        Route::get('/service-purchases', [ServicePurchaseController::class, 'index'])->name('admin.service.purchases');
        Route::put('/service/purchase/{id}/approve', [ServicePurchaseController::class, 'approve'])->name('admin.service.purchase.approve');
        Route::put('/service/purchase/{id}/reject', [ServicePurchaseController::class, 'reject'])->name('admin.service.purchase.reject');
        Route::delete('/service/purchase/{id}', [ServicePurchaseController::class, 'destroy'])->name('admin.service.purchase.destroy');
        Route::get('/facebook-ad-requests', [ServicePurchaseController::class, 'facebookAdRequests'])->name('admin.facebook-ad-requests');
        Route::resource('facebook-pages', FacebookPageController::class)->names('admin.facebook-pages');
        Route::put('facebook-pages/{id}/toggle-status', [FacebookPageController::class, 'toggleStatus'])->name('admin.facebook-pages.toggleStatus');



    });
});


Route::middleware([ 'auth','role:user,customer'])->group(function ()  {
    Route::prefix('user')->group(function ()  {
        Route::get('/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
        Route::patch('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
        Route::patch('/profile/changePhoto', [UserProfileController::class, 'changePhoto'])->name('user.profile.changePhoto');
        Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('user.profile.destroy');
        Route::get('/wallet', [UserWalletController::class, 'index'])->name('user.wallet.index');
        Route::post('/wallet/recharge', [UserWalletController::class, 'recharge'])->name('user.wallet.recharge');
        Route::get('/transactions', [UserWalletTransactionController::class, 'index'])->name('user.transactions.index');
        Route::get('/services/{id}', [UserServiceController::class, 'show'])->name('user.services.show');
        Route::post('/services/facebook-ad/buy', [UserServiceController::class, 'buyFacebookAdService'])->name('user.services.facebook_ad.buy');
    });
});

Route::middleware([ 'auth','role:customer'])->group(function ()  {
    Route::prefix('customer')->group(function ()  {
        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
        Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
        Route::patch('/profile/changePhoto', [CustomerProfileController::class, 'changePhoto'])->name('customer.profile.changePhoto');
        Route::delete('/profile', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');
    });

});

// SSLCOMMERZ Start
Route::middleware(['auth'])->group(function () {
    Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);
    Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
    Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);
});
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);

require __DIR__.'/auth.php';
