<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anak extends Model
{
    use HasFactory;

    protected $table = 'anak';

    protected $fillable= [
        'nama',
        'tanggalLahir'
    ];
}
