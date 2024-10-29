<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin; 

class AdminProfileController extends Controller
{
    public function edit()
    {
        // Logika untuk menampilkan form edit profil admin
        $admin = auth()->user(); // Mengambil data admin yang sedang login
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
    

        $admin = auth()->user();
        $admin->name = $request->name;
        $admin->email = $request->email;
    

        if ($request->hasFile('profile_photo')) {

            if ($admin->profile_photo) {
                \Storage::delete($admin->profile_photo);
            }
    

            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $admin->profile_photo = $path; 
        }
    
        $admin->save();
    
        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = auth()->user();


        if (!\Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Kata Sandi saat ini tidak sesuai!']);
        }


        $admin->password = \Hash::make($request->new_password);
        $admin->save();

        return redirect()->route('admin.profile.edit')->with('status', 'Kata Sandi berhasil diperbarui!'); // Ganti dengan status yang sesuai
    }
    

    public function destroy()
    {

        $admin = auth()->user();
        $admin->delete();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus!');
    }
}
