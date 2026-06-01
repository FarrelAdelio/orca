<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\WasteController;
use App\Http\Controllers\AuthController;

// ========== AUTH ROUTES ==========
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== PROTECTED ROUTES (Harus Login) ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WasteController::class, 'dashboard'])->name('dashboard');
    Route::get('/controller', function () {
        return view('controller');
    })->name('controller');
    
    Route::post('/move-to-history', [LogController::class, 'move']);
    
    // API Routes
    Route::get('/api/waste/latest', [WasteController::class, 'getLatest']);
    Route::get('/api/waste/history', [WasteController::class, 'getHistory']);
    Route::get('/api/waste/stats', [WasteController::class, 'getStats']);
    Route::post('/api/waste/move-to-history/{id}', [WasteController::class, 'moveToHistory']);
    Route::post('/api/waste/move-all-to-history', [WasteController::class, 'moveAllToHistory']);
    Route::post('/api/waste/sync', [WasteController::class, 'syncToMySQL']);
});

// ========== REDIRECT ROOT ==========
Route::get('/', function () {
    return redirect()->route('login');
});