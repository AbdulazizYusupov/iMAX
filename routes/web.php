<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('dashboard/categories', CategoryController::class);
    Route::resource('dashboard/phones', PhoneController::class);
    Route::resource('dashboard/expenses', ExpenseController::class);
    Route::resource('dashboard/sales', SaleController::class)->only(['index', 'create', 'store']);
    Route::post('dashboard/sales/{sale}/pay-month', [SaleController::class, 'payNextMonth'])->name('sales.payMonth');

    Route::get('dashboard/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('dashboard/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
