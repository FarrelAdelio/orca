<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Http\Controllers\LogController;

Route::post('/move-to-history', [LogController::class, 'move']);

Route::get('/controller', function () {
    return view('controller');
})->name('controller');