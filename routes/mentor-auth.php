<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Mentor\Auth\LoginController;
use App\Http\Controllers\Mentor\Auth\RegisteredUserController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\Mentor\MentorProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('mentor')->middleware('guest:mentor')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('mentor.register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])
        ->name('mentor.login');

    Route::post('login', [LoginController::class, 'store']);
});

Route::prefix('mentor')->middleware('auth:mentor')->group(function () {

    Route::get('/dashboard', function () {
        return view('mentor.dashboard');
    })->name('mentor.dashboard');

    Route::post('logout', [LoginController::class, 'destroy'])->name('mentor.logout');

    Route::get('/mentor/profile/edit', [MentorProfileController::class, 'edit'])->name('mentor.profile.edit');
    Route::post('/mentor/profile/update', [MentorProfileController::class, 'update'])->name('mentor.profile.update');
    Route::patch('/mentor/profile/update', [MentorProfileController::class, 'update'])->name('mentor.profile.update.patch');
    Route::put  ('/mentor/profile/update-password', [MentorProfileController::class, 'updatePassword'])->name('mentor.password.update'); // Pastikan rute ini ada
    Route::delete('/mentor/profile/delete', [MentorProfileController::class, 'destroy'])->name('mentor.profile.destroy');

    Route::get('/mentor/users', [MentorController::class, 'usersIndex'])->name('mentor.users.user');

});


