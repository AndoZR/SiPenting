<?php

namespace App\Models;

use App\Models\hist_gizi; // ✅ Ini model Eloquent
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bayi extends Model
{
    use HasFactory;

    protected $table = 'bayi';

    public $timestamps = false;

    protected $fillable= [
        'nama',
        'tanggalLahir',
        'kelamin',
        'id_users'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function hist_gizi()
    {
        return $this->hasMany(hist_gizi::class, 'id_bayi');  // 'village_id' adalah foreign key di tabel users
    }
}
