<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// didapatkan dari vendor/laravel/fortify/src/http/controllers/registeredUserController
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
// vendor/laravel/fortify/src/Http/Controller
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;

// Untuk melindungi rute sehingga semua permintaan yang masuk harus diautentikasi, Anda harus melampirkan sanctum pelindung autentikasi ke rute
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::middleware('auth:sanctum')->post('logout', [AuthenticatedSessionController::class, 'destroy']);
// Jadi begini cuk ada url api/user yg akan mengambil detail user, nanti aku akan gunakan itu di dashboard.vue pake axios untuk mengambil detail dan melakukan two way binding
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// categories
Route::middleware('auth:sanctum')->post('/categories/create', [CategoryController::class, 'store']);
Route::middleware('auth:sanctum')->get('/categories', [CategoryController::class, 'index'])->name('categories.index');

