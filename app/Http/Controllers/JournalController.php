<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JournalController extends Controller
{
    // Method untuk menampilkan jurnal pengguna yang sedang login
    public function index()
    {
        // Ambil jurnal hanya untuk pengguna yang sedang login
        $journals = Journal::where('user_id', Auth::id())->get()->map(function ($journal) {
            $journal->formatted_date = Carbon::parse($journal->date)->translatedFormat('j F Y');
            // Format waktu menjadi HH:MM
            $journal->start_time = Carbon::parse($journal->start_time)->format('H:i');
            $journal->end_time = Carbon::parse($journal->end_time)->format('H:i');
            return $journal;
        });

        return view('journal.index', compact('journals'));
    }

    // Method untuk menampilkan halaman form pembuatan jurnal
    public function create()
    {
        return view('journal.create');
    }

    // Method untuk menyimpan data jurnal baru
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        // Simpan data jurnal dengan waktu dalam format yang benar
        Journal::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time)->format('H:i'),
            'end_time' => Carbon::createFromFormat('H:i', $request->end_time)->format('H:i')
        ]));

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil ditambahkan');
    }

    // Method untuk menampilkan halaman edit jurnal
    public function edit($id)
    {
        $journal = Journal::findOrFail($id);

        // Pastikan pengguna hanya bisa mengedit jurnal miliknya sendiri
        if ($journal->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('journals.index')->with('error', 'Anda tidak diizinkan mengedit jurnal ini.');
        }

        // Format waktu untuk ditampilkan di form edit
        $journal->start_time = Carbon::parse($journal->start_time)->format('H:i');
        $journal->end_time = Carbon::parse($journal->end_time)->format('H:i');

        return view('journal.edit', compact('journal'));
    }

    // Method untuk mengupdate data jurnal
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        $journal = Journal::findOrFail($id);

        // Pastikan pengguna hanya bisa mengupdate jurnal miliknya sendiri
        if ($journal->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('journals.index')->with('error', 'Anda tidak diizinkan mengupdate jurnal ini.');
        }

        // Update data jurnal dengan waktu dalam format yang benar
        $journal->update(array_merge($request->all(), [
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time)->format('H:i'),
            'end_time' => Carbon::createFromFormat('H:i', $request->end_time)->format('H:i'),
        ]));

        return redirect()->route('journals.index')->with('success', 'Aktivitas berhasil diupdate.');
    }

    // Method untuk menampilkan semua jurnal ke halaman admin
    public function adminIndex()
    {
        $journals = Journal::with('user')->get()->map(function ($journal) {
            $journal->formatted_date = Carbon::parse($journal->date)->translatedFormat('j F Y');
            // Format waktu menjadi HH:MM
            $journal->start_time = Carbon::parse($journal->start_time)->format('H:i');
            $journal->end_time = Carbon::parse($journal->end_time)->format('H:i');
            return $journal;
        });

        return view('journal.admin', compact('journals'));
    }
    
    // Method untuk menghapus jurnal
    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);

        // Pastikan hanya admin atau pemilik jurnal yang bisa menghapus
        if ($journal->user_id !== Auth::id() && !(Auth::check() && Auth::user()->is_admin)) {
            return redirect()->route('journals.index')->with('error', 'Anda tidak diizinkan menghapus jurnal ini.');
        }

        $journal->delete();

        // Arahkan sesuai status pengguna
        return redirect()->route(Auth::user()->is_admin ? 'journal.admin' : 'journals.index')->with('success', 'Jurnal berhasil dihapus');
    }
}
