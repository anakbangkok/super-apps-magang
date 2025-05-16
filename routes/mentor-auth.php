<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Mentor\Auth\RegisteredUserController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\Mentor\MentorProfileController;
use App\Http\Controllers\Mentor\TimSosmedMentorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasukanController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\Mentor\TimWebMentorController;


Route::prefix('mentor')->middleware('guest:mentor')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('mentor.register');

    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::prefix('mentor')->middleware('auth:mentor')->group(function () {
    Route::get('/dashboard', function () {
        return view('mentor.dashboard');
    })->name('mentor.dashboard');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('mentor.logout');

    Route::get('/mentor/profile/edit', [MentorProfileController::class, 'edit'])->name('mentor.profile.edit');
    Route::post('/mentor/profile/update', [MentorProfileController::class, 'update'])->name('mentor.profile.update');
    Route::patch('/mentor/profile/update', [MentorProfileController::class, 'update'])->name('mentor.profile.update.patch');
    Route::put  ('/mentor/profile/update-password', [MentorProfileController::class, 'updatePassword'])->name('mentor.password.update'); // Pastikan rute ini ada
    Route::delete('/mentor/profile/delete', [MentorProfileController::class, 'destroy'])->name('mentor.profile.destroy');

    Route::get('/mentor/users', [MentorController::class, 'usersIndex'])->name('mentor.users.user');

    // Route::resource('tim_sosmeds', TimSosmedMentorController::class);
    Route::get('/tim_sosmeds', [TimSosmedMentorController::class, 'index'])->name('mentor.tim_sosmeds.index');
    Route::get('/tim_sosmeds/{tim_sosmed}', [TimSosmedMentorController::class, 'show'])->name('mentor.tim_sosmeds.show');
    Route::get('/tim_sosmeds/{tim_sosmed}/edit', [TimSosmedMentorController::class, 'edit'])->name('mentor.tim_sosmeds.edit');
    Route::put('/tim_sosmeds/{tim_sosmed}/update', [TimSosmedMentorController::class, 'update'])->name('mentor.tim_sosmeds.update');
    Route::delete('/tim_sosmeds/{tim_sosmed}/destroy', [TimSosmedMentorController::class, 'destroy'])->name('mentor.tim_sosmeds.destroy');

    Route::get('/mentor/masukan', [MasukanController::class, 'mentor'])->name('masukan.mentor');

    Route::get('/mentor/journals', [AktivitasController::class, 'mentorindex'])->name('journal.mentor');

    Route::get('tim_web', [TimWebMentorController::class, 'index'])->name('mentor.tim_webs.index');
    Route::get('tim_web/{tim_web}', [TimWebMentorController::class, 'show'])->name('mentor.tim_webs.show');
    Route::get('tim_web/create', [TimWebMentorController::class, 'create'])->name('mentor.tim_webs.create');
    Route::post('tim_web/store', [TimWebMentorController::class, 'store'])->name('mentor.tim_webs.store');
    Route::get('tim_web/{tim_web}/edit', [TimWebMentorController::class, 'edit'])->name('mentor.tim_webs.edit');
    Route::put('tim_web/{tim_web}/update', [TimWebMentorController::class, 'update'])->name('mentor.tim_webs.update');
    Route::delete('tim_web/{tim_web}/destroy', [TimWebMentorController::class, 'destroy'])->name('mentor.tim_webs.destroy');
    

});


