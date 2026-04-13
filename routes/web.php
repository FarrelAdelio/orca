<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\WasteController;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', [WasteController::class, 'dashboard'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/move-to-history', [LogController::class, 'move']);

Route::get('/controller', function () {
    return view('controller');
})->name('controller');

// API Routes untuk data (tetap)
Route::get('/api/waste/latest', [WasteController::class, 'getLatest']);
Route::get('/api/waste/history', [WasteController::class, 'getHistory']);
Route::get('/api/waste/stats', [WasteController::class, 'getStats']);
Route::post('/api/waste/move-to-history/{id}', [WasteController::class, 'moveToHistory']);
Route::post('/api/waste/move-all-to-history', [WasteController::class, 'moveAllToHistory']);  // ← TAMBAHKAN
Route::post('/api/waste/sync', [WasteController::class, 'syncToMySQL']);
