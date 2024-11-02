<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    
    public function getFormattedDateAttribute()
    {
  
        setlocale(LC_TIME, 'id_ID.UTF-8');
        return Carbon::parse($this->date)->translatedFormat('j F Y');
    }
}
