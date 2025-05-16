<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
{
    public function adminIndex()
    {
        $acara = Acara::latest()->get();
        return view('admin.acara.index', compact('acara'));
    }

    public function dashboard()
    {
        $acara = Acara::orderBy('tanggal', 'desc')->get();

        return view('dashboard', compact('acara'));
    }


    public function create()
    {
        return view('admin.acara.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('acara', 'public');
        }

        Acara::create($data);
        return redirect()->route('admin.acara.index')->with('success', 'Acara berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $acara = Acara::findOrFail($id);
        return view('admin.acara.edit', compact('acara'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'tanggal' => 'required|date',
            'lokasi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048'
        ]);
    
        $acara = Acara::findOrFail($id);
        
        // Jika ada gambar baru, simpan dan hapus yang lama
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($acara->gambar) {
                storage::delete('public/' . $acara->gambar);
            }
    
            // Simpan gambar baru
            $gambarPath = $request->file('gambar')->store('acara', 'public');
            $acara->gambar = $gambarPath;
        }
    
        // Update data acara
        $acara->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
        ]);
    
        return redirect()->route('admin.acara.index')->with('success', 'Acara berhasil diperbarui.');
    }
    

    public function destroy($id)
    {
        $acara = Acara::findOrFail($id);
        if ($acara->gambar) {
            Storage::disk('public')->delete($acara->gambar);
        }
        $acara->delete();
        return redirect()->route('admin.acara.index')->with('success', 'Acara berhasil dihapus!');
    }
}
