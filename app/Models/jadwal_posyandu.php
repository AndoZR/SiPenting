<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class jadwal_posyandu extends Model
{
    use HasFactory;

    protected $table = 'jadwal_posyandu';

    public $timestamps = false;

    protected $fillable = [
        'id_posyandu',
        'tanggal',
        'waktu',
        'id_users',
        'deskripsi',
    ];

    // Accessor to format 'waktu'
    public function getWaktuAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }
}
