<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Bu yerda loyihangizning barcha marshrutlari (routerlari) joylashgan.
| Laravel 13 standartlariga mos va toliq optimallashtirilgan.
|
*/

// --- BARCHA FOYDALANUVCHILAR KO'RADIGAN ASOSIY SAHIFA ---
Route::get('/', function () {
    return view('welcome'); // Telefonlar galereyasi yoki do'konning asosiy vitrinasi
})->name('home');


// --- MEHMONLAR UCHUN (Tizimga kirmaganlar ko'ra oladi xolos) ---
Route::middleware('guest')->group(function () {

    // Login sahifasini ko'rsatish (Chiroyli to'q rangli/neon dizaynli blade)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // Login qilish jarayoni (Forma yuborilganda eslab qolish funksiyasi bilan ishlaydi)
    Route::post('/login', [AuthController::class, 'login']);
});


// --- ADMIN VA TIZIMGA KIRGANLAR UCHUN (Himoyalangan Admin Panel) ---
Route::middleware('auth')->group(function () {

    // Tizimdan chiqish (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin panel bosh sahifasi (Dashboard)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- KATEGORIYALAR BOSHQARUVI (CRUD) ---
    // Bu bitta qator index, create, store, edit, update, destroy yo'llarini ochib beradi
    Route::resource('dashboard/categories', CategoryController::class);
    Route::resource('dashboard/phones', PhoneController::class);
    Route::resource('dashboard/expenses', ExpenseController::class);

    Route::get('dashboard/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('dashboard/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');


});
