<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimSosmed extends Model
{
    use HasFactory;

    protected $table = 'tim_sosmeds';

    protected $fillable = [
        'user_id',
        'nama',                // Menambahkan kolom 'nama' ke dalam $fillable
        'pekerjaan_hari_ini',
        'keterangan',
        'tanggal',
    ];

    // Kolom yang otomatis diisi oleh timestamps
    public $timestamps = true;

    /**
     * Relasi ke model User.
     * Setiap TimSosmed dimiliki oleh satu pengguna (user).
     */
    public function user()
    {
        return $this->belongsTo(User::class);  // Relasi belongsTo ke model User
    }
    public function getFormattedTanggalAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }
}


