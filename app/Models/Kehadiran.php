<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kehadirans';

    // Kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id', 'shift', 'date', 'check_in', 'check_out', 'location',
    ];

    /**
     * Relasi ke model User (many-to-one).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adminIndex()
    {
        // Mengambil semua data absensi dengan relasi user
        $kehadirans = Kehadiran::with('user')->get();
        return view('admin.kehadiran.index', compact('kehadirans'));
    }

}

