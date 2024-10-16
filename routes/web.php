<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KehadiranController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadirans.index');
    Route::post('/kehadiran/checkin', [KehadiranController::class, 'checkIn'])->name('kehadirans.checkin');
    Route::post('/kehadiran/checkout/{id}', [KehadiranController::class, 'checkOut'])->name('kehadirans.checkout');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/kehadiran', [KehadiranController::class, 'adminIndex'])->name('admin.kehadiran.index');
    Route::delete('/kehadirans/{id}', [KehadiranController::class, 'destroy'])->name('kehadirans.destroy');

});


require __DIR__.'/auth.php';

require __DIR__.'/admin-auth.php';
