<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;



class ProfileController extends Controller
{
    /**
     * Menampilkan form profil user.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil user.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Isi data user dengan data yang telah divalidasi
        $validatedData = $request->validated();
        $user->fill($validatedData);

        // Periksa apakah email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null; // Reset verifikasi email
        }

        if ($request->filled('password')) {
            // Validasi password baru jika diperlukan, misalnya panjang minimal
            $user->password = Hash::make($request->input('password'));
        }

        // Cek apakah ada file foto profil yang diunggah
        if ($request->hasFile('profile_photo')) {
            try {
                // Hapus foto profil lama jika ada
                if ($user->profile_photo && Storage::exists($user->profile_photo)) {
                    Storage::delete($user->profile_photo);
                }

                // Simpan foto profil baru
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $user->profile_photo = $path;
            } catch (\Exception $e) {
                return Redirect::back()->withErrors(['profile_photo' => 'Gagal mengunggah foto profil.']);
            }
        }

        // Simpan perubahan user jika ada perubahan
        if ($user->isDirty()) {
            $user->save();
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        // Kembalikan jika tidak ada perubahan
        return Redirect::route('profile.edit')->with('status', 'no-changes');
    }

    /**
     * Menghapus akun user.
     */
    public function destroy()
    {


        $user = Auth()->user();

        // Menghapus foto profil jika ada
        if ($user->profile_photo) {
            \Storage::delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus!');
    }
}
