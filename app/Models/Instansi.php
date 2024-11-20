<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $fillable = ['nama_instansi'];

     // Tambahkan relasi 'users'
     public function users()
     {
         return $this->hasMany(User::class, 'instansi_id');  // 'instansi_id' adalah foreign key di tabel 'users'
     }
}

