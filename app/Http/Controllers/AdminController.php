<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    // Menampilkan form pembuatan akun
    public function index()
    {
        $admins = Admin::paginate(10); // Mengambil data admin dan membatasi 10 per halaman
        return view('admin.auth.index', compact('admins'));
    }
    public function create()
    {
        return view('admin.auth.create');
    }

    // Menyimpan data admin baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ],
        [
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        // Membuat akun admin baru
        $admin = new Admin;
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->password = Hash::make($validated['password']);
        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dibuat!');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.auth.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,  
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        $admin = Admin::findOrFail($id);
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];


        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus!');
    }
}
