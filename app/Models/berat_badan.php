<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class berat_badan extends Model
{
    use HasFactory;

    protected $table = 'berat_badan';

    protected $fillable = [
        'bbNow',
        'id_users',
        'created_at',
        'updated_at'
    ];


    // Relasi bb ke User
    public function users()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
