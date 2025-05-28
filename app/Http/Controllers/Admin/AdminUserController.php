<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Mentor;
use App\Models\Penugasan;
use App\Models\Instansi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TimWeb;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use App\Models\Acara;




class AdminUserController extends Controller
{
    // Di dalam controller
    public function index(Request $request)
    {
        // Mengambil query filter
        $query = $this->applyFilters($request);

        $users = $query->get();

        // Mendapatkan data penugasan untuk dropdown
        $penugasans = Penugasan::all();
        $instansis = Instansi::all();
        $mentors = Mentor::all();
        // Jika request AJAX, hanya return table fragment
        if ($request->ajax()) {
            return view('admin.users.table', compact('users'));
        }

        // Return halaman utama dengan data
        return view('admin.users.index', compact('users', 'penugasans', 'instansis', 'mentors'));
    }



    public function create()
    {
        // Ambil semua data terkait untuk dropdown
        $instansis = Instansi::all();
        $penugasans = Penugasan::all();
        $mentors = Mentor::all();

        return view('admin.users.create', compact('instansis', 'penugasans', 'mentors'));
    }

    public function updateAllStatuses()
    {
        $users = User::all();
        $currentDate = now();

        foreach ($users as $user) {
            if ($user->start_date && $user->end_date) {
                if ($currentDate->between($user->start_date, $user->end_date)) {
                    $user->status = 'aktif';
                } elseif ($currentDate->greaterThan($user->end_date)) {
                    $user->status = 'selesai';
                } else {
                    $user->status = 'belum masuk';
                }
                $user->save();
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Status semua pengguna berhasil diperbarui.');
    }


    public function store(Request $request)
    {
        // Validasi data permintaan yang masuk
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'instansi' => 'required|exists:instansis,id',
                'penugasan' => 'required|exists:penugasans,id',
                'mentor' => 'required|exists:mentors,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'password' => 'required|string|min:8|confirmed',
            ],
            [
                'email.unique' => 'Email sudah terdaftar.',
            ]
        );


        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];


        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'instansi_id' => $validatedData['instansi'],
            'penugasan_id' => $validatedData['penugasan'],
            'mentor_id' => $validatedData['mentor'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $instansis = Instansi::all();
        $penugasans = Penugasan::all();
        $mentors = Mentor::all();

        return view('admin.users.edit', compact('user', 'instansis', 'penugasans', 'mentors'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Aturan validasi
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'instansi' => 'required|exists:instansis,id',
            'penugasan' => 'required|exists:penugasans,id',
            'mentor' => 'required|exists:mentors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];

        // Tambahkan validasi password hanya jika diisi
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        // Custom pesan error
        $messages = [
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
        ];

        // Validasi input
        $request->validate($rules, $messages);

        // Update data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->instansi_id = $request->instansi;
        $user->penugasan_id = $request->penugasan;
        $user->mentor_id = $request->mentor;
        $user->start_date = $request->start_date;
        $user->end_date = $request->end_date;

        // Update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Periksa status berdasarkan tanggal
        $currentDate = now();

        if ($currentDate->between($user->start_date, $user->end_date)) {
            $user->status = 'aktif';
        } elseif ($currentDate->greaterThan($user->end_date)) {
            $user->status = 'selesai';
        } else {
            $user->status = 'belum masuk';
        }

        // Simpan perubahan
        $user->save();

        // Redirect
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function dashboard()
    {
        $belumMasuk = User::where('start_date', '>', now()->toDateString())->count();
        $aktif = User::where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())->count();
        $selesai = User::where('end_date', '<', now()->toDateString())->count();
        $belumDiisi = User::whereNull('start_date')->whereNull('end_date')->count();

        $topUsers = DB::table('tim_web')
            ->select('user_id', DB::raw('SUM(jumlah_kata) as total_kata'))
            ->groupBy('user_id')
            ->orderByDesc('total_kata')
            ->get();

        // Lakukan eager loading untuk mengambil informasi pengguna
        $topUsers = $topUsers->map(function ($item) {
            $user = User::find($item->user_id);
            $item->name = $user ? $user->name : 'Nama tidak ditemukan';
            $item->profile_photo_path = $user ? $user->profile_photo_path : null; // Menambahkan foto profil
            $item->instansi_name = $user && $user->instansi ? $user->instansi->nama_instansi : 'Tidak Ada Instansi'; // Jika ada relasi instansi
            $item->profile_photo = $user ? $user->profile_photo : null;
            return $item;
        });
        // Mengambil data pengguna yang sedang login
        $currentUser = Auth::user();

        // Mendapatkan total kata dari pengguna yang sedang login
        $currentUserTotalKata = DB::table('tim_web')
            ->where('user_id', $currentUser->id)
            ->sum('jumlah_kata');

        // Menentukan peringkat pengguna yang sedang login
        $userRank = $topUsers->firstWhere('user_id', $currentUser->id);
        $userRankPosition = 0;

        if ($userRank) {
            $userRankPosition = $topUsers->search(function ($item) use ($userRank) {
                return $item->user_id == $userRank->user_id;
            }) + 1;
        }

        $acara = Acara::latest()->take(3)->get();

        return view('admin.dashboard', compact('belumMasuk', 'aktif', 'selesai', 'belumDiisi', 'topUsers', 'userRankPosition', 'currentUserTotalKata', 'acara'));
    }


    public function updatestatus()
    {
        $today = Carbon::today();

        // Update status for each user
        User::where('start_date', '>', $today)->update(['status' => 'Belum Masuk']);
        User::whereBetween($today, ['start_date', 'end_date'])->update(['status' => 'Aktif']);
        User::where('end_date', '<', $today)->update(['status' => 'Selesai']);
    }

    protected function applyFilters(Request $request)
    {
        $searchName = $request->input('searchName');
        $searchEmail = $request->input('searchEmail');
        $searchPenugasan = $request->input('searchPenugasan');
        $searchInstansi = $request->input('searchInstansi');
        $searchStatus = $request->input('searchStatus');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $userId = $request->input('user'); // Ambil ID pengguna yang dipilih

        $query = User::with(['instansi', 'penugasan', 'mentor']);

        // Filter berdasarkan nama
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        // Filter berdasarkan email
        if ($searchEmail) {
            $query->where('email', 'like', '%' . $searchEmail . '%');
        }

        // Filter berdasarkan penugasan
        if ($searchPenugasan) {
            $query->where('penugasan_id', $searchPenugasan);
        }

        // Filter
        if ($searchInstansi) {
            $query->where('instansi_id', $searchInstansi);
        }

        // Filter berdasarkan status
        if ($searchStatus) {
            $now = now()->toDateString();

            switch ($searchStatus) {
                case 'Aktif':
                    $query->where('start_date', '<=', $now)->where('end_date', '>=', $now);
                    break;
                case 'Belum Masuk':
                    $query->where('start_date', '>', $now);
                    break;
                case 'Selesai':
                    $query->where('end_date', '<', $now);
                    break;
            }
        }

        // Filter berdasarkan pengguna yang dipilih
        if ($userId) {
            $query->where('id', $userId); // Menambahkan filter berdasarkan ID pengguna
        }

        // Filter berdasarkan rentang tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('start_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('end_date', '<=', $endDate);
        }

        return $query;
    }

    public function export(Request $request)
    {
        // Validasi input filter
        $request->validate([
            'instansi_id' => 'nullable|exists:instansis,id', // pastikan instansi_id valid
            'status' => 'nullable|in:Belum Masuk,Aktif,Selesai',
            'start_from' => 'nullable|date',
            'start_to' => 'nullable|date|after_or_equal:start_from',
        ]);

        // Mengambil data filter dari request
        $instansiId = $request->get('instansi_id');
        $status = $request->get('status');
        $startFrom = $request->get('start_from') ? Carbon::parse($request->get('start_from')) : null;
        $startTo = $request->get('start_to') ? Carbon::parse($request->get('start_to')) : null;

        // Query untuk mendapatkan data berdasarkan filter
        $query = User::query();

        // Menerapkan filter berdasarkan input
        if ($instansiId) {
            $query->where('instansi_id', $instansiId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startFrom && $startTo) {
            $query->whereBetween('start_date', [$startFrom, $startTo]);
        } elseif ($startFrom) {
            $query->where('start_date', '>=', $startFrom);
        } elseif ($startTo) {
            $query->where('start_date', '<=', $startTo);
        }

        // Menjalankan query dan mengambil data pengguna yang sudah difilter
        $users = $query->get();

        // Mengekspor data yang sudah difilter ke file Excel
        return Excel::download(new UsersExport($users), 'data_pengguna.xlsx');
    }



    public function import(Request $request)
    {
        // Pastikan file yang dipilih benar dan di-upload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Proses import file
        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('admin.users.index')->with('success', 'Data berhasil diimport!');
    }



    public function downloadTemplate()
    {
        // Path ke file template
        $path = storage_path('app/templates/user_import_template.xlsx');

        // Cek apakah file ada
        if (!file_exists($path)) {
            return abort(404, 'File template tidak ditemukan.');
        }

        // Return response file
        return response()->download($path, 'user_import_template.xlsx');
    }

    public function dataTimWeb()
    {
        $today = Carbon::today();

        // Hitung jumlah artikel dan kata hari ini
        $jumlahArtikelHariIni = TimWeb::whereDate('tanggal', $today)->sum('jumlah_artikel');
        $jumlahKataHariIni = TimWeb::whereDate('tanggal', $today)->sum('jumlah_kata');

        // Hitung total jumlah artikel dan kata semua user
        $totalJumlahArtikel = TimWeb::sum('jumlah_artikel');
        $totalJumlahKata = TimWeb::sum('jumlah_kata');

        // Ambil data semua tim web
        $timWebs = TimWeb::with('user')->get();

        return view('admin.tim_web.index', [
            'tim_webs' => $timWebs,
            'jumlahArtikel' => $jumlahArtikelHariIni,
            'jumlahKata' => $jumlahKataHariIni,
            'totalJumlahArtikel' => $totalJumlahArtikel,
            'totalJumlahKata' => $totalJumlahKata,
        ]);
    }
}
