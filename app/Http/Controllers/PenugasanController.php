<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function index()
    {
        $penugasans = Penugasan::all();
        return view('admin.penugasan.index', compact('penugasans'));
    }

    public function create()
    {
        return view('admin.penugasan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit_bisnis' => 'required|string|max:255',
        ]);

        Penugasan::create($request->all());
        return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil ditambahkan!');
    }

    public function edit(Penugasan $penugasan)
    {
        return view('admin.penugasan.edit', compact('penugasan'));
    }

    public function update(Request $request, Penugasan $penugasan)
    {
        $request->validate([
            'nama_unit_bisnis' => 'required|string|max:255',
        ]);

        $penugasan->update($request->all());
        return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil diperbarui!');
    }

    public function destroy(Penugasan $penugasan)
    {
        $penugasan->delete();
        return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil dihapus!');
    }
}
