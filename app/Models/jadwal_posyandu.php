<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
