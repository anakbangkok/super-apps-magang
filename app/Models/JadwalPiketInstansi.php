<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPiketInstansi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_piket_instansi';
    protected $fillable = ['jadwal_piket_id', 'instansi_id'];
}

