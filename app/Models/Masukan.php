<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masukan extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';
    protected $fillable = ['message', 'name', 'email', 'user_id', 'is_read'];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

