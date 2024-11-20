<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin'; // Nama tabel yang digunakan
    protected $fillable = [
        'user_id', 'durasi', 'jenis_izin', 'tanggal_mulai',
        'tanggal_selesai', 'jumlah_hari', 'keterangan', 'status'
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
