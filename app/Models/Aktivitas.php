<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Aktivitas extends Model
{
    use HasFactory;

    protected $table = 'journals';
    protected $fillable = [
        'user_id',   // ID pengguna yang membuat jurnal
        'date',      // Tanggal aktivitas
        'name',      // Nama pengguna (bisa diambil dari relasi)
        'instansi_id',
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

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id', 'id');
    }
    
    public function getFormattedDateAttribute()
    {
  
        setlocale(LC_TIME, 'id_ID.UTF-8');
        return Carbon::parse($this->date)->translatedFormat('j F Y');
    }
}
