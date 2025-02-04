<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// didapatkan dari vendor/laravel/fortify/src/http/controllers/registeredUserController
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
// vendor/laravel/fortify/src/Http/Controller
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardPostsController;

Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
// Untuk melindungi rute sehingga semua permintaan yang masuk harus diautentikasi, Anda harus melampirkan sanctum pelindung autentikasi ke rute
Route::middleware('auth:sanctum')->post('logout', [AuthenticatedSessionController::class, 'destroy']);
// Jadi begini cuk ada url api/user yg akan mengambil detail user, nanti aku akan gunakan itu di dashboard.vue pake axios untuk mengambil detail dan melakukan two way binding
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// categories
// karena aku tidak tau cara mengambil token login maka aku hanya bisa melakukan ini jika mencoba fitur simpan kategori
// Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
Route::middleware('auth:sanctum')->post('/categories/create', [CategoryController::class, 'store']);
Route::middleware('auth:sanctum')->get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::middleware('auth:sanctum')->get('/categories/{category}', [CategoryController::class, 'show']);
// Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::middleware('auth:sanctum')->put('/categories/{category}', [CategoryController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/categories/{category}', [CategoryController::class, 'destroy']);

// menu home
Route::get('/home-posts', [HomeController::class, 'index'])->name('home.index');

// menu blog
Route::get('/posts', [PostController::class, 'index'])->name('post.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('post.show');

// menu dashboard
Route::middleware('auth:sanctum')->get('/dashboard-posts', [DashboardPostsController::class, 'index']);
Route::middleware('auth:sanctum')->post('/posts', [PostController::class, 'store'])->name('posts.store');
// {post:slug} adalah pengikatan router model agar aku bisa mengambil detail postingan di controller
Route::middleware('auth:sanctum')->put('/posts/{post:slug}', [PostController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/posts/{post:slug}', [PostController::class, 'destroy']);
