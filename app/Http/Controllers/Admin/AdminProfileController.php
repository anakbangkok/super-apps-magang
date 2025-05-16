<?php

// ada bug

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function edit()
    {
        // Logika untuk menampilkan form edit profil admin
        $admin = auth('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();
        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($admin->profile_photo && Storage::exists($admin->profile_photo)) {
                Storage::delete($admin->profile_photo);
            }

            // Store new profile photo
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $admin->profile_photo = $path;
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input dengan pesan kustom
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            // Pesan kustom untuk 'current_password'
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',

            // Pesan kustom untuk 'new_password'
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        // Verifikasi kata sandi saat ini
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai!']);
        }

        if (Hash::check($request->new_password, $admin->password)) {
            return back()->withErrors(['new_password' => 'Kata sandi baru tidak boleh sama dengan yang lama.']);
        }

        // Update kata sandi
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('admin.profile.edit')->with('status', 'Kata sandi berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        // Validate current password before deletion
        $request->validate([
            'current_password' => 'required|string',
        ]);
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        // Verify the current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Kata Sandi saat ini tidak sesuai!']);
        }

        // Log out and delete the admin account
        Auth::logout();
        $admin->delete();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus!');
    }
}
