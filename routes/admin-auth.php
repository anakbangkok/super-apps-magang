<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\KehadiranExportController;
use App\Http\Controllers\Admin\PengajuanIzinUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MasukanController;
use App\Http\Controllers\JadwalPiketController;
use App\Http\Controllers\AktivitasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KehadiranController;
use App\Http\Middleware\CheckPendingCount;
use App\Http\Controllers\TimWebController;

Route::prefix('admin')->middleware('guest:admin')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminUserController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/index', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
    // Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    // Route::put('/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/destroy/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

    Route::get('/admin/kehadiran', [KehadiranController::class, 'adminIndex'])->name('admin.kehadiran.index');
    Route::get('admin/kehadiran/export', [App\Http\Controllers\KehadiranController::class, 'export'])->name('admin.kehadiran.export');
    Route::get('/kehadiran/export', [KehadiranController::class, 'export'])->name('kehadiran.export');

    Route::resource('admin/users', AdminUserController::class)->except(['show'])->names('admin.users');
    Route::get('admin/users/export', [AdminUserController::class, 'export'])->name('admin.users.export');
    Route::post('/users/import', [AdminUserController::class, 'import'])->name('admin.users.import');
    Route::get('/download-template', [AdminUserController::class, 'downloadTemplate'])->name('admin.users.downloadTemplate');



    Route::get('/admin/pengajuan-izin', [PengajuanIzinUserController::class, 'index'])->name('pengajuan_izin.index');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/approve', [PengajuanIzinUserController::class, 'approve'])->name('pengajuan_izin.approve');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/reject', [PengajuanIzinUserController::class, 'reject'])->name('pengajuan_izin.reject');
    Route::get('/admin/pengajuan-izin/notifications', [PengajuanIzinUserController::class, 'notifications'])->name('pengajuan_izin.notifications');
    Route::get('pengajuan-izin/export', [PengajuanIzinUserController::class, 'export'])->name('admin.pengajuan_izin.export');


    Route::post('masukan/{id}/reply', [MasukanController::class, 'reply'])->name('masukan.reply');
    Route::get('/admin/aktivitas', [AktivitasController::class, 'adminIndex'])->name('aktiviyas.admin');
    Route::get('/aktivitass/export', [AktivitasController::class, 'export'])->name('aktivitas.export');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');

    Route::get('tim_webs/edit/{tim_web}', [TimWebController::class, 'edit'])->name('tim_web.edit');
    Route::put('tim_webs/{tim_web}', [TimWebController::class, 'update'])->name('tim_web.update');
    Route::delete('tim_webs/{tim_web}', [TimWebController::class, 'destroy'])->name('tim_web.destroy');

    Route::get('/admin/acara', [AcaraController::class, 'adminIndex'])->name('admin.acara.index');
    Route::get('/admin/acara/create', [AcaraController::class, 'create'])->name('admin.acara.create');
    Route::post('/admin/acara', [AcaraController::class, 'store'])->name('admin.acara.store');
    Route::get('/admin/acara/{id}/edit', [AcaraController::class, 'edit'])->name('admin.acara.edit');
    Route::put('/admin/acara/{id}', [AcaraController::class, 'update'])->name('admin.acara.update');
    Route::delete('/admin/acara/{id}', [AcaraController::class, 'destroy'])->name('admin.acara.destroy');

    Route::resource('jadwal_piket', JadwalPiketController::class);
});

Route::get('/check-notifications', [PengajuanIzinUserController::class, 'checkNotifications'])->name('pengajuan_izin.check_notifications');
