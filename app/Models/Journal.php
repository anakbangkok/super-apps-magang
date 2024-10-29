<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // ID pengguna yang membuat jurnal
        'date',      // Tanggal aktivitas
        'name',      // Nama pengguna (bisa diambil dari relasi)
        'start_time', // Jam mulai aktivitas
        'end_time',   // Jam selesai aktivitas
        'activity'   // Deskripsi aktivitas
    ];

    /**
     * Relasi dengan model User.
     * 
     * Ini memungkinkan kita untuk mengakses informasi pengguna dari jurnal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

