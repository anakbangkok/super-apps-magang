<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\PengajuanIzinUserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\JournalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KehadiranController;

Route::prefix('admin')->middleware('guest:admin')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('login', [LoginController::class, 'store']);

});

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/dashboard', [AdminUserController::class, 'dashboard'])->name('admin.dashboard');


    Route::get('/admin/kehadiran', [KehadiranController::class, 'adminIndex'])->name('admin.kehadiran.index');

    Route::resource('admin/users', AdminUserController::class)->names('admin.users');

    Route::get('/admin/pengajuan-izin', [PengajuanIzinUserController::class, 'index'])->name('pengajuan_izin.index');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/approve', [PengajuanIzinUserController::class, 'approve'])->name('pengajuan_izin.approve');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/reject', [PengajuanIzinUserController::class, 'reject'])->name('pengajuan_izin.reject');

    Route::post('feedback/{id}/reply', [FeedbackController::class, 'reply'])->name('feedback.reply');

    Route::get('/admin/journals', [JournalController::class, 'adminIndex'])->name('journal.admin');



    Route::post('logout', [LoginController::class, 'destroy'])->name('admin.logout');

});