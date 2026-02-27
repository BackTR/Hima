<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');//public
});

Route::middleware(['auth'])->group(function (){

    Route::get('/dashboard',[DashboardController::class,'index'])
    ->middleware(['role:superadmin,admin,pengurus,anggota'])
    ->name('dashboard'); //dashboard untuk semua role

     // Profile - semua role bisa akses
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    //super admin & admin only
    Route::middleware(['auth', 'role:superadmin,admin'])->group(function(){
        Route::resource('members', MemberController::class);
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });

    // Superadmin, Admin & Pengurus
    Route::middleware('role:superadmin,admin,pengurus')->group(function () {
       Route::resource('events', EventController::class);
       Route::get('events/{events_id}/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
       Route::post('events/{events_id}/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
       Route::delete('events/{event_id}/attendances/{id}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
    // untuk event
    });

});
require __DIR__.'/auth.php';
