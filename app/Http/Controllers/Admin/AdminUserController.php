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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TimWeb;


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
    
        // Jika request AJAX, hanya return table fragment
        if ($request->ajax()) {
            return view('admin.users.table', compact('users'));
        }
    
        // Return halaman utama dengan data
        return view('admin.users.index', compact('users', 'penugasans'));
    }
    


    public function create()
    {
        // Ambil semua data terkait untuk dropdown
        $instansis = Instansi::all();
        $penugasans = Penugasan::all();
        $mentors = Mentor::all();

        return view('admin.users.create', compact('instansis', 'penugasans', 'mentors'));
    }

    public function store(Request $request)
    {
        // Validasi data permintaan yang masuk
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'instansi' => 'required|exists:instansis,id',
            'penugasan' => 'required|exists:penugasans,id',
            'mentor' => 'required|exists:mentors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat pengguna baru dengan data yang divalidasi
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'instansi_id' => $validatedData['instansi'],
            'penugasan_id' => $validatedData['penugasan'],
            'mentor_id' => $validatedData['mentor'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        // Ambil data terkait untuk form edit
        $instansis = Instansi::all();
        $penugasans = Penugasan::all();
        $mentors = Mentor::all();

        return view('admin.users.edit', compact('user', 'instansis', 'penugasans', 'mentors'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data permintaan yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'instansi' => 'required|exists:instansis,id',
            'penugasan' => 'required|exists:penugasans,id',
            'mentor' => 'required|exists:mentors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Perbarui pengguna dengan data yang divalidasi
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'instansi_id' => $request->instansi,
            'penugasan_id' => $request->penugasan,
            'mentor_id' => $request->mentor,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui!');
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

        return view('admin.dashboard', compact('belumMasuk', 'aktif', 'selesai', 'belumDiisi', 'topUsers', 'userRankPosition', 'currentUserTotalKata'));
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
        // Terapkan filter ke query
        $query = $this->applyFilters($request);

        // Ambil data yang sudah difilter dengan kolom yang dibutuhkan
        $users = $query->get(['name', 'email', 'penugasan_id', 'mentor_id', 'start_date', 'end_date', 'status']);

        // Pastikan bahwa hanya data terfilter yang dikirim ke `UsersExport`
        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang cocok dengan filter.');
        }

        return Excel::download(new UsersExport($users), 'users_export.xlsx');
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
