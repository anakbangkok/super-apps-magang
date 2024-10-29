<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mentor; 

class MentorProfileController extends Controller
{
    // Menampilkan form edit profil mentor
    public function edit()
    {
        $mentor = auth()->user();
        return view('mentor.profile.edit', compact('mentor'));
    }
    
    // Memperbarui profil mentor
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $mentor = auth()->user();
        $mentor->name = $request->name;
        $mentor->email = $request->email;
    
        if ($request->hasFile('profile_photo')) {
            // Menghapus foto profil lama jika ada
            if ($mentor->profile_photo) {
                \Storage::delete($mentor->profile_photo);
            }
    
            // Menyimpan foto profil baru
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $mentor->profile_photo = $path; 
        }
    
        $mentor->save();
    
        return redirect()->route('mentor.profile.edit')->with('status', 'Profil berhasil diperbarui!');
    }

    // Memperbarui kata sandi mentor
    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $mentor = auth()->user();

        // Memeriksa kata sandi saat ini
        if (!\Hash::check($request->current_password, $mentor->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai!']);
        }

        // Memperbarui kata sandi
        $mentor->password = \Hash::make($request->new_password);
        $mentor->save();

        return redirect()->route('mentor.profile.edit')->with('status', 'Kata sandi berhasil diperbarui!');
    }

    // Menghapus akun mentor
    public function destroy()
    {
        $mentor = auth()->user();
        
        // Menghapus foto profil jika ada
        if ($mentor->profile_photo) {
            \Storage::delete($mentor->profile_photo);
        }

        $mentor->delete();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus!');
    }
}
