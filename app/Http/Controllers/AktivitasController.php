<?php

namespace App\Http\Controllers;

use App\Exports\JournalsExport;
use App\Models\Aktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JournalsImport;
use Illuminate\Support\Facades\Storage;
use App\Models\Instansi;


class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = Aktivitas::where('user_id', Auth::id());
    
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }
    
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }
    
        $journals = $query->get()->map(function ($journal) {
            $journal->formatted_time = Carbon::parse($journal->date)->format('H:i');
            return $journal;
        });
    
        return view('aktivitas.index', compact('journals'));
    }
    

    public function create()
    {
        return view('aktivitas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        Aktivitas::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time)->format('H:i'),
            'end_time' => Carbon::createFromFormat('H:i', $request->end_time)->format('H:i')
        ]));

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas Harian berhasil ditambahkan');
    }

    public function edit($id)
    {
        $journal = Aktivitas::findOrFail($id);

        if ($journal->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('journals.index')->with('error', 'Anda tidak diizinkan mengedit jurnal ini.');
        }

        $journal->start_time = Carbon::parse($journal->start_time)->format('H:i');
        $journal->end_time = Carbon::parse($journal->end_time)->format('H:i');

        return view('aktivitas.edit', compact('journal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:255',
        ]);

        $journal = Aktivitas::findOrFail($id);

        // Pastikan pengguna hanya bisa mengupdate jurnal miliknya sendiri
        if ($journal->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('aktivitas.index')->with('error', 'Anda tidak diizinkan mengupdate jurnal ini.');
        }

        // Update data jurnal dengan waktu dalam format yang benar
        $journal->update(array_merge($request->all(), [
            'start_time' => Carbon::createFromFormat('H:i', $request->start_time)->format('H:i'),
            'end_time' => Carbon::createFromFormat('H:i', $request->end_time)->format('H:i'),
        ]));

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas Harian berhasil diupdate.');
    }

    // Method untuk menampilkan semua jurnal ke halaman admin
    public function adminIndex(Request $request)
    {

        // Terapkan filter menggunakan metode applyFilters
        $query = $this->applyFilters($request);


        // Ambil data jurnal dengan pagination
        $journals = $query->paginate(10)->appends($request->query());

        // Ambil data pengguna dan instansi untuk dropdown
        $users = User::all();
        $instansis = Instansi::all();

        return view('aktivitas.admin', compact('journals', 'users', 'instansis'));
    }

    public function mentorIndex(Request $request)
    {
        // Ambil ID mentor yang sedang login
        $mentorId = Auth::id();

        // Ambil semua user_id dari user yang dibimbing oleh mentor ini
        $userIds = User::where('mentor_id', $mentorId)->pluck('id');

        // Ambil data jurnal hanya dari user yang dibimbing
        $journals = Aktivitas::whereIn('user_id', $userIds)
            ->with('user.instansi') // untuk akses $journal->user->instansi
            ->paginate(10);

        // Ambil user & instansi untuk ditampilkan jika perlu
        $users = User::whereIn('id', $userIds)->get();
        $instansis = Instansi::all();

        return view('aktivitas.mentor', compact('journals', 'users', 'instansis'));
    }


    public function destroy($id)
    {
        $journal = Aktivitas::findOrFail($id);


        if (Auth::guard('admin')->check()) {
            $journal->delete();
            Log::info("Jurnal dengan ID " . $id . " berhasil dihapus oleh admin.");

            return redirect()->route('aktivitas.admin')->with('success', 'Aktivitas Harian berhasil dihapus');
        }

        if (Auth::guard('web')->check() && $journal->user_id === Auth::id()) {
            $journal->delete();
            Log::info("Jurnal dengan ID " . $id . " berhasil dihapus oleh user dengan ID " . Auth::id() . ".");

            return redirect()->route('aktivitas.index')->with('success', 'Aktivitas Harian berhasil dihapus');
        }
        return redirect()->route('aktivitas.index')->with('error', 'Anda tidak diizinkan menghapus jurnal ini.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new JournalsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadExample()
    {
        $filePath = storage_path('app/templates/template_aktifitas_harian.xlsx');
        $fileName = 'template_aktifitas_harian.xlsx';

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File contoh tidak ditemukan.');
        }

        return response()->download($filePath, $fileName);
    }


    public function export(Request $request)
    {
        // Ambil filter dari form
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $user_name = $request->input('user_name');

        // Terapkan filter jika ada
        $journals = Aktivitas::query();

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

    public function applyFilters(Request $request)
    {
        // Mulai dengan query dasar untuk mengambil semua jurnal
        $query = Aktivitas::query();

        // Filter berdasarkan tanggal mulai
        if ($request->filled('start_date')) {
            $query->where('date', '>=', Carbon::parse($request->start_date)->format('Y-m-d'));
        }

        // Filter berdasarkan tanggal selesai
        if ($request->filled('end_date')) {
            $query->where('date', '<=', Carbon::parse($request->end_date)->format('Y-m-d'));
        }

        // Filter berdasarkan nama pengguna
        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', $request->user_name);
            });
        }

        // Filter berdasarkan instansi
        if ($request->filled('instansi_id')) {
            $query->whereHas('user.instansi', function ($q) use ($request) {
                $q->where('id', $request->instansi_id);
            });
        }

        // Kembalikan query setelah penyaringan
        return $query;
    }
}
