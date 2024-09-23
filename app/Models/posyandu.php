<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posyandu extends Model
{
    use HasFactory;

    protected $table = 'posyandu';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'lokasi',
        'lat',
        'lng',
        'kontak',
        'id_users',
        'id_villages'
    ];
}
