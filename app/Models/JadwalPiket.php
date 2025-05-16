<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPiket extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pikets'; // Nama tabel di database
    protected $fillable = ['tanggal'];

    public function instansis()
    {
        return $this->belongsToMany(Instansi::class, 'jadwal_piket_instansi', 'jadwal_piket_id', 'instansi_id');
    }
}

