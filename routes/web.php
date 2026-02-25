<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',
    [App\Http\Controllers\DashboardController::class,'index'])
->middleware(['auth', 'role:superadmin,admin,pengurus,anggota'])->name('dashboard');

Route::middleware(['auth', 'role:superadmin,admin'])->group(function(){
    Route::resource('members', MemberController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
