<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class makanan extends Model
{
    use HasFactory;

    protected $table = 'makanan';

    protected $fillable = [
        'nama',
        'gambar',
        'deskripsi',
        'satuan',
    ];
}
