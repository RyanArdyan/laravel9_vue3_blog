<?php

use Illuminate\Support\Facades\Route;

// {any} berarti karakter apapun yang dimasukkan user
Route::get('/{any}', function () {
    return view('welcome');
    // . berarti jika user tidak memasukkan / setelah http://127.0.0.1:8000
})->where('any', '.*');
