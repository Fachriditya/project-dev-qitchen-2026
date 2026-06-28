<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ArticleController as PublicArticleController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

use App\Http\Controllers\Cashier\TransactionController;

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

// ==========================================
// ADMIN ROUTING
// ==========================================

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    
    // Nanti rute AdminController lainnya (Menu, Article, dll) akan bersarang di sini
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