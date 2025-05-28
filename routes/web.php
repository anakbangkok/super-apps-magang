<?php

use App\Http\Controllers\AcaraController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\PengajuanIzinUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MasukanController;
use App\Http\Controllers\PengajuanIzinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\TimWebController;
use App\Http\Controllers\TimSosmedUserController;
use App\Http\Controllers\TimSosmedController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\TimWebUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mentor\TimWebMentorController;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\JadwalPiketController;

Route::get('/', function () {
    return view('login');
});

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Group rute dengan middleware auth
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'event'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadirans.index');
    Route::post('/kehadiran/checkin', [KehadiranController::class, 'checkIn'])->name('kehadirans.checkin');
    Route::post('/kehadiran/checkout/{id}', [KehadiranController::class, 'checkOut'])->name('kehadirans.checkout');

    // Pengajuan izin user
    Route::get('/pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('pengajuan_izin.index');
    Route::post('/pengajuan-izin', [PengajuanIzinController::class, 'store'])->name('pengajuan_izin.store');
        Route::get('/pengajuan-izin/create', [PengajuanIzinController::class, 'create'])->name('pengajuan_izin.create');

    Route::get('/masukan/create', [MasukanController::class, 'create'])->name('masukan.create');
    Route::post('/masukan', [MasukanController::class, 'store'])->name('masukan.store');
    Route::get('/masukan', [MasukanController::class, 'index'])->name('masukan.index');

    // Route::resource('journals', AktivitasController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas.index');
    Route::get('/aktivitas/tambah', [AktivitasController::class, 'create'])->name('aktivitas.create');
    Route::get('/aktivitas/edit/{id}', [AktivitasController::class, 'edit'])->name('aktivitas.edit');
    Route::post('/aktivitas', [AktivitasController::class, 'store'])->name('aktivitas.store');
    Route::put('/aktivitas/{id}', [AktivitasController::class, 'update'])->name('aktivitas.update');
    Route::delete('/aktivitas/{id}', [AktivitasController::class, 'destroy'])->name('aktivitas.destroy');
    Route::delete('/aktivitas/{id}/destroy', [AktivitasController::class, 'destroy'])->name('journal.admin.destroy');
    Route::post('/aktivitas/import', [AktivitasController::class, 'import'])->name('journals.import.store');
    Route::get('/aktivitas/example', [AktivitasController::class, 'downloadExample'])->name('journals.example');




    Route::resource('tim_webs', TimWebUserController::class);
    Route::get('/tim_webs', [TimWebUserController::class, 'index'])->name('tim_webs.index');
    Route::get('/tim_webs/{tim_web}', [TimWebUserController::class, 'show'])->name('tim_webs.show');

    Route::resource('tim_sosmeds', TimSosmedUserController::class);
    Route::get('/tim_sosmeds', [TimSosmedUserController::class, 'index'])->name('tim_sosmeds.index');
    Route::get('/tim_sosmeds/create', [TimSosmedUserController::class, 'create'])->name('tim_sosmeds.create');
    Route::post('/tim_sosmeds', [TimSosmedUserController::class, 'store'])->name('tim_sosmeds.store');
    Route::get('/tim_sosmeds/{tim_sosmed}', [TimSosmedUserController::class, 'show'])->name('tim_sosmeds.show');

    Route::get('/jadwal-piket', [JadwalPiketController::class, 'userJadwal'])->name('jadwal.index');
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


    Route::resource('/admin/tim_web', TimWebController::class);
    Route::resource('tim_sosmed', TimSosmedController::class);

    Route::get('/admin/users/search', [AdminUserController::class, 'search'])->name('admin.users.search');

    // Pengajuan izin admin
    Route::get('/admin/pengajuan-izin', [PengajuanIzinUserController::class, 'index'])->name('admin.pengajuan_izin.index');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/approve', [PengajuanIzinUserController::class, 'approve'])->name('admin.pengajuan_izin.approve');
    Route::post('/admin/pengajuan-izin/{pengajuanIzin}/reject', [PengajuanIzinUserController::class, 'reject'])->name('admin.pengajuan_izin.reject');
    Route::get('/check-notifications', [PengajuanIzinController::class, 'checkNotifications'])->name('pengajuan_izin.check_notifications');


    Route::get('/admin/masukan', [MasukanController::class, 'admin'])->name('masukan.admin');
    Route::delete('/masukan/{masukan}', [MasukanController::class, 'destroy'])->name('masukan.destroy');

    Route::get('admin/mentor', [MentorController::class, 'index'])->name('mentor.index');
    Route::get('admin/mentor/create', [MentorController::class, 'create'])->name('mentor.create');
    Route::post('admin/mentor', [MentorController::class, 'store'])->name('mentor.store');
    Route::get('admin/mentor/{mentor}', [MentorController::class, 'show'])->name('mentor.show');
    Route::get('admin/mentor/{mentor}/edit', [MentorController::class, 'edit'])->name('mentor.edit');
    Route::put('admin/mentor/{mentor}', [MentorController::class, 'update'])->name('mentor.update');
    Route::delete('admin/mentor/{mentor}', [MentorController::class, 'destroy'])->name('mentor.destroy');

    // Rute untuk admin jurnal

    Route::get('/admin/aktivitas', [AktivitasController::class, 'adminIndex'])->name('aktivitas.admin');
    Route::delete('/admin/aktivitas/{id}', [AktivitasController::class, 'destroy'])->name('aktivitas.admin.destroy');

    Route::resource('/admin/admin/tim_web_admin', TimWebController::class);

    // Route::resource('/admin/journals', JournalController::class)->only(['index', 'create', 'store']);


});

// Group rute dengan middleware auth:mentor
Route::middleware(['auth:mentor'])->group(function () {
    // Route::get('/mentor/feedback', [MasukanController::class, 'mentor'])->name('feedback.mentor');

    // Route::get('/mentor/journals', [JournalController::class, 'mentorindex'])->name('journal.mentor');

    // Route::resource('tim_web', TimWebMentorController::class);
    // Route::get('tim_web', [TimWebMentorController::class, 'index'])->name('mentor.tim_webs.index');
    // Route::get('tim_web/{tim_web}', [TimWebMentorController::class, 'show'])->name('mentor.tim_webs.show');
    
});

// Memasukkan file auth tambahan
require __DIR__ . '/auth.php';
require __DIR__ . '/admin-auth.php';
require __DIR__ . '/mentor-auth.php';
