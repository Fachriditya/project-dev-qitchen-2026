<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ArticleController as PublicArticleController;

use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\TrackingController;
use App\Http\Controllers\Api\MidtransWebhookController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\TransactionController as AdminTransaction;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ArticleController;

use App\Http\Controllers\Cashier\TransactionController as CashierTransaction;
use App\Http\Controllers\Cashier\ManualOrderController;
use App\Http\Controllers\Cashier\PickupController;

use App\Http\Controllers\Kitchen\KitchenController;

use App\Http\Controllers\Courier\DeliveryOfferController;

use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\VoucherController as CustomerVoucherController;
use App\Http\Controllers\Customer\ReferralController;

// ==========================================
// GOOGLE ROUTING
// ==========================================

Route::middleware(['guest'])->group(function () {
    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    
    Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.post');
    
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('custom.password.update');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// ==========================================
// PUBLIC ROUTING
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/articles', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [PublicArticleController::class, 'show'])->name('articles.show');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/voucher/apply', [CheckoutController::class, 'applyVoucher'])->name('checkout.apply-voucher');
Route::post('/checkout/voucher/remove', [CheckoutController::class, 'removeVoucher'])->name('checkout.remove-voucher');
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handler']);
Route::get('/track/{order_number}', [TrackingController::class, 'show'])->name('tracking.show');

// ==========================================
// ADMIN ROUTING
// ==========================================

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::resource('menus', AdminMenuController::class)->names('admin.menus');
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('vouchers', VoucherController::class)->names('admin.vouchers');
    Route::resource('transactions', AdminTransaction::class)->only(['index', 'show'])->names('admin.transactions');
    Route::resource('deliveries', DeliveryController::class)->only(['index', 'show'])->names('admin.deliveries');

    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');

    Route::resource('articles', ArticleController::class)->names('admin.articles');
    

});

// ==========================================
// CASHIER ROUTING
// ==========================================

Route::middleware(['auth', 'isCashier'])->prefix('cashier')->group(function () {
    
    Route::get('/transactions', [CashierTransaction::class, 'index'])->name('cashier.transactions.index');
    Route::get('/transactions/{id}', [CashierTransaction::class, 'show'])->name('cashier.transactions.show');

    Route::get('/manual-order', [ManualOrderController::class, 'index'])->name('cashier.manual-order.index');
    Route::post('/manual-order/add/{id}', [ManualOrderController::class, 'add'])->name('cashier.manual-order.add');
    Route::post('/manual-order/update/{id}', [ManualOrderController::class, 'update'])->name('cashier.manual-order.update');
    Route::post('/manual-order/process', [ManualOrderController::class, 'process'])->name('cashier.manual-order.process');
    Route::get('/manual-order/payment/{id}', [ManualOrderController::class, 'payment'])->name('cashier.manual-order.payment');
    Route::post('/manual-order/payment/{id}/complete', [ManualOrderController::class, 'completePayment'])->name('cashier.manual-order.complete');

    Route::get('/pickup', [PickupController::class, 'index'])->name('cashier.pickup.index');
    Route::post('/pickup/{id}/confirm', [PickupController::class, 'confirm'])->name('cashier.pickup.confirm');
    
});

// ==========================================
// KITCHEN ROUTING
// ==========================================

Route::middleware(['auth', 'isKitchen'])->prefix('kitchen')->group(function () {
    Route::get('/kitchen/board', [KitchenController::class, 'index'])->name('kitchen.board');
    Route::post('/kitchen/order/{id}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.update-status');
});

// ==========================================
// COURIER ROUTING
// ==========================================

Route::middleware(['auth', 'isCourier'])->prefix('courier')->group(function () {
    Route::get('/offers', [DeliveryOfferController::class, 'index'])->name('courier.offers');
    Route::post('/offers/{id}/accept', [DeliveryOfferController::class, 'accept'])->name('courier.accept');

    Route::get('/my-deliveries', [DeliveryOfferController::class, 'myDeliveries'])->name('courier.my-deliveries');
    
    Route::get('/map/{id}', [DeliveryOfferController::class, 'map'])->name('courier.map');
    Route::post('/map/{id}/update-location', [DeliveryOfferController::class, 'updateLocation'])->name('courier.update-location');
    Route::post('/map/{id}/complete', [DeliveryOfferController::class, 'complete'])->name('courier.complete');
});

// ==========================================
// CUSTOMER ROUTING
// ==========================================

// Legacy Breeze compatibility: dashboard alias
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/profile/history', [OrderController::class, 'index'])->name('profile.history');
    Route::get('/profile/vouchers', [CustomerVoucherController::class, 'index'])->name('profile.vouchers');
    Route::get('/profile/referral', [ReferralController::class, 'index'])->name('profile.referral');
});;

require __DIR__.'/auth.php';