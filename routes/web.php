<?php

use App\Http\Controllers\logincontroller;
use App\Http\Controllers\registercontroller;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('main.cuahangmaytinh');
})->name('cuahangmaytinh');

Route::get('/register', function () {
    return view('verify.register');
})->name('register');

Route::get('/login', function () {
    return view('verify.login');
})->name('login');

Route::post('/register', function () {
    return redirect()->route('cuahangmaytinh');
})->name('register.submit');

Route::post('/login', [logincontroller::class, 'login']);
Route::post('/register', [registercontroller::class, 'register']);

Route::post('/login', function () {
    return redirect()->route('cuahangmaytinh');
})->name('login.submit');

Route::get('/{path}', function () {
    return redirect()->route('login');
})->where('path', 'shop(?:\.blade\.php)?|single-product-page(?:\.blade\.php)?|checkout(?:\.blade\.php)?|cart(?:\.blade\.php)?');
