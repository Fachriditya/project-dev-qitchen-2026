<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Kitchen\KitchenBoardController;

use App\Http\Controllers\Courier\DeliveryOfferController;

use App\Http\Controllers\ProfileController;

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
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handler']);
Route::get('/track/{order_number}', [TrackingController::class, 'show'])->name('tracking.show');

// ==========================================
// ADMIN ROUTING
// ==========================================

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::resource('admin/menus', AdminMenuController::class)->names('admin.menus');
    Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
    Route::resource('admin/users', UserController::class)->names('admin.users');
    Route::resource('admin/vouchers', VoucherController::class)->names('admin.vouchers');
    Route::resource('admin/transactions', AdminTransaction::class)->only(['index', 'show'])->names('admin.transactions');
    Route::resource('admin/deliveries', DeliveryController::class)->only(['index', 'show'])->names('admin.deliveries');
    Route::get('admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::resource('articles', ArticleController::class)->names('admin.articles');
    

});

// ==========================================
// CASHIER ROUTING
// ==========================================

Route::middleware(['auth', 'isCashier'])->prefix('cashier')->group(function () {
    // URL-nya kita buat spesifik ke '/transactions'
    Route::get('/transactions', [TransactionController::class, 'index'])->name('cashier.transactions');
});

// ==========================================
// KITCHEN ROUTING
// ==========================================

Route::middleware(['auth', 'isKitchen'])->prefix('kitchen')->group(function () {
    Route::get('/board', [KitchenBoardController::class, 'index'])->name('kitchen.board');
});

// ==========================================
// COURIER ROUTING
// ==========================================

Route::middleware(['auth', 'isCourier'])->prefix('courier')->group(function () {
    Route::get('/offers', [DeliveryOfferController::class, 'index'])->name('courier.offers');
});

// ==========================================
// CUSTOMER ROUTING
// ==========================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';