<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    // Menambahkan kolom yang dapat diisi ke dalam fillable
    protected $fillable = ['nama_unit_bisnis'];

    /**
     * Definisikan relasi dengan model User.
     * Jika satu Penugasan bisa dimiliki oleh banyak User, gunakan hasMany.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'penugasan_id'); // 'penugasan_id' adalah foreign key di tabel 'users'
    }
}
