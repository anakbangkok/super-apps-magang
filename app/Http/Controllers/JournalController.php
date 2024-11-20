<?php

namespace App\Http\Controllers;

use App\Exports\JournalsExport;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
    public function adminIndex(Request $request)
    {
        // Get all users for the filter dropdown
        $users = User::all();

        // Build the query for journals, with optional filters
        $journalsQuery = Journal::with('user');

        // Filter by start and end date
        if ($request->has('start_date') && $request->start_date) {
            $journalsQuery->where('date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }

        if ($request->has('end_date') && $request->end_date) {
            $journalsQuery->where('date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }

        // Filter by user name (optional)
        if ($request->has('user_name') && $request->user_name) {
            $journalsQuery->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->user_name}%");
            });
        }

        // Get the journals with all filters applied
        $journals = $journalsQuery->get()->map(function ($journal) {
            $journal->formatted_date = Carbon::parse($journal->date)->translatedFormat('j F Y');
            // Format time as HH:MM
            $journal->start_time = Carbon::parse($journal->start_time)->format('H:i');
            $journal->end_time = Carbon::parse($journal->end_time)->format('H:i');
            return $journal;
        });

        // Return to the view with the journals and users
        return view('journal.admin', compact('journals', 'users'));
    }

    public function destroy($id)
    {
        $journal = Journal::findOrFail($id);
    
        // Jika user adalah admin, langsung hapus jurnal
        if (Auth::guard('admin')->check()) {
            $journal->delete();
            Log::info("Jurnal dengan ID " . $id . " berhasil dihapus oleh admin.");
    
            return redirect()->route('journal.admin')->with('success', 'Jurnal berhasil dihapus');
        }
    
        // Jika user bukan admin, periksa apakah jurnal dimiliki oleh user
        if (Auth::guard('web')->check() && $journal->user_id === Auth::id()) {
            $journal->delete();
            Log::info("Jurnal dengan ID " . $id . " berhasil dihapus oleh user dengan ID " . Auth::id() . ".");
    
            return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dihapus');
        }
    
        // Jika tidak memenuhi syarat, tampilkan pesan error
        return redirect()->route('journals.index')->with('error', 'Anda tidak diizinkan menghapus jurnal ini.');
    }
    



    public function export(Request $request)
    {
        // Ambil filter dari form
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $user_name = $request->input('user_name');

        // Terapkan filter jika ada
        $journals = Journal::query();

        if ($start_date) {
            $journals->where('date', '>=', $start_date);
        }

        if ($end_date) {
            $journals->where('date', '<=', $end_date);
        }

        if ($user_name) {
            $journals->whereHas('user', function ($query) use ($user_name) {
                $query->where('name', 'like', "%$user_name%");
            });
        }

        // Ekspor ke Excel
        return Excel::download(new JournalsExport($journals), 'journals.xlsx');
    }
}
