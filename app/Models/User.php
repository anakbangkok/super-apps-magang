<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'instansi_id',
        'penugasan_id', 
        'mentor_id',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Define the relationship to the Instansi model.
     */

     public function getStartDateAttribute($value)
     {
         return Carbon::parse($value)->format('Y-m-d H:i:s');
     }
 
     /**
      * Accessor untuk end_date
      */
     public function getEndDateAttribute($value)
     {
         return Carbon::parse($value)->format('Y-m-d H:i:s');
     }
 
    public function instansi() {
        return $this->belongsTo(Instansi::class);
    }
    
    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'penugasan_id');
    }
    
    public function mentor() {
        return $this->belongsTo(Mentor::class);
    }

    public function masukans() {
        return $this->hasMany(Masukan::class);
    }
    
}
