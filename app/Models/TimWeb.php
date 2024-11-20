<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TimWeb extends Model
{
    use HasFactory;

    protected $table = 'tim_web';

    protected $fillable = [
        'nama',
        'jumlah_artikel',
        'jumlah_kata',
        'keterangan',
        'tanggal',
        'user_id',  // Tambahkan kolom user_id agar bisa diisi secara massal
    ];

    // Definisikan relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTanggalAttribute()
    {
        return \Carbon\Carbon::parse($this->tanggal)->translatedFormat('d F Y');
    }
}
