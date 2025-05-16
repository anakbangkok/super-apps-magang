<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index()
    {
        $instansis = Instansi::all();
        return view('admin.Instansi.index', compact('instansis')); 
    }

    public function create()
    {
        return view('admin.Instansi.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
        ]);

        Instansi::create($request->all());
        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil ditambahkan!');
    }

    public function edit(Instansi $instansi)
    {
        return view('admin.Instansi.edit', compact('instansi')); 
    }

    public function update(Request $request, Instansi $instansi)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
        ]);

        $instansi->update($request->all());
        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil diperbarui!');
    }

    public function destroy(Instansi $instansi)
    {
        $instansi->delete();
        return redirect()->route('instansi.index')->with('success', 'Instansi berhasil dihapus!');
    }
}
