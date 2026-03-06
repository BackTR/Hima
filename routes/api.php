<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnggotaController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profil
    Route::get('/profil', [AnggotaController::class, 'profil']);
    Route::patch('/profil', [AnggotaController::class, 'updateProfil']);
    Route::patch('/ganti-password', [AnggotaController::class, 'gantiPassword']);

    // Riwayat kehadiran
    Route::get('/riwayat', [AnggotaController::class, 'riwayat']);

    // Event & Scan
    Route::get('/events', [AnggotaController::class, 'events']);
    Route::get('/absen/{token}', [AnggotaController::class, 'scan']);
});