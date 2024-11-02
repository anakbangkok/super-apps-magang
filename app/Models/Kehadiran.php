<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kehadiran extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kehadirans';

    // Kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id', 'shift', 'date', 'check_in', 'check_out', 'location',
    ];

    /**
     * Relasi ke model User (many-to-one).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Aksesors untuk memformat tanggal
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->translatedFormat('d-F-Y'); 
    }

    public function getFormattedCheckInAttribute()
    {
        return $this->check_in ? Carbon::parse($this->check_in)->setTimezone('Asia/Jakarta')->format('H:i') : '-';
    }

    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out ? Carbon::parse($this->check_out)->setTimezone('Asia/Jakarta')->format('H:i') : '-';
    }
}
