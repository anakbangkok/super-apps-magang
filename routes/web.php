<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\PengajuanIzinUserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PengajuanIzinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\TimWebController;
use App\Http\Controllers\TimSosmedController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JournalController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group rute dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadirans.index');
    Route::post('/kehadiran/checkin', [KehadiranController::class, 'checkIn'])->name('kehadirans.checkin');
    Route::post('/kehadiran/checkout/{id}', [KehadiranController::class, 'checkOut'])->name('kehadirans.checkout');

    // Pengajuan izin user
    Route::get('/pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('pengajuan_izin.index');
    Route::get('/pengajuan-izin/create', [PengajuanIzinController::class, 'create'])->name('pengajuan_izin.create');
    Route::post('/pengajuan-izin', [PengajuanIzinController::class, 'store'])->name('pengajuan_izin.store');

    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    
    Route::resource('journals', JournalController::class)->only(['index', 'create', 'store']);
});

// Group rute dengan middleware auth:admin
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/profile/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/password/update', [AdminProfileController::class, 'updatePassword'])->name('admin.password.update');
    Route::delete('/admin/profile/destroy', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');

    Route::get('/admin/kehadiran', [KehadiranController::class, 'adminIndex'])->name('admin.kehadiran.index');
    Route::delete('/kehadirans/{id}', [KehadiranController::class, 'destroy'])->name('kehadirans.destroy');

    Route::resource('/admin/instansi', InstansiController::class);
    Route::resource('/admin/mentors', MentorController::class);
    Route::resource('/admin/penugasan', PenugasanController::class);
    Route::resource('/admin/users', AdminUserController::class);

    Route::resource('/admin/tim_web', TimWebController::class);
    Route::resource('tim_sosmed', TimSosmedController::class);

    Route::get('/admin/users/search', [AdminUserController::class, 'search'])->name('admin.users.search');

    // Pengajuan izin admin
    Route::get('/admin/pengajuan-izin', [PengajuanIzinUserController::class, 'index'])->name('admin.pengajuan_izin.index');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/approve', [PengajuanIzinUserController::class, 'approve'])->name('admin.pengajuan_izin.approve');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/reject', [PengajuanIzinUserController::class, 'reject'])->name('admin.pengajuan_izin.reject');

    Route::get('/admin/feedback', [FeedbackController::class, 'admin'])->name('feedback.admin');
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

    Route::get('admin/mentor', [MentorController::class, 'index'])->name('mentor.index');
    Route::get('admin/mentor/create', [MentorController::class, 'create'])->name('mentor.create');
    Route::post('admin/mentor', [MentorController::class, 'store'])->name('mentor.store');
    Route::get('admin/mentor/{mentor}', [MentorController::class, 'show'])->name('mentor.show');
    Route::get('admin/mentor/{mentor}/edit', [MentorController::class, 'edit'])->name('mentor.edit');
    Route::put('admin/mentor/{mentor}', [MentorController::class, 'update'])->name('mentor.update');
    Route::delete('admin/mentor/{mentor}', [MentorController::class, 'destroy'])->name('mentor.destroy');

    // Rute untuk admin jurnal
    Route::get('/admin/journals', [JournalController::class, 'adminIndex'])->name('journal.admin');
    Route::delete('/journals/{id}', [JournalController::class, 'destroy'])->name('journal.admin.destroy');

    // Route::resource('/admin/journals', JournalController::class)->only(['index', 'create', 'store']);
});

// Group rute dengan middleware auth:mentor
Route::middleware(['auth:mentor'])->group(function () {
    Route::get('/mentor/feedback', [FeedbackController::class, 'mentor'])->name('feedback.mentor');
});

// Memasukkan file auth tambahan
require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
require __DIR__.'/mentor-auth.php';
