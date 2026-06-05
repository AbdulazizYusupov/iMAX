<?php

use App\Http\Controllers\{AuthController, CategoryController, ExpenseController, HomeController, PhoneController, ProfileController, SaleController};
use Illuminate\Support\Facades\Route;

// Asosiy sahifani dashboard'ga yo'naltirish
Route::redirect('/', '/dashboard');

// Mehmondagi (Tizimga kirmagan) foydalanuvchilar uchun
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Avtorizatsiyadan o'tgan foydalanuvchilar uchun (Dashboard va barcha resurslar)
Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    // Resurslar (Prefiks hisobiga URL avtomat 'dashboard/categories' ko'rinishida bo'ladi)
    Route::resource('categories', CategoryController::class);
    Route::resource('phones', PhoneController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store']);
    Route::post('sales/{sale}/pay-month', [SaleController::class, 'payNextMonth'])->name('sales.payMonth');

    // Profil sozlamalari
    Route::controller(ProfileController::class)->prefix('profile')->as('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::put('password', 'updatePassword')->name('password.update');
    });
});
