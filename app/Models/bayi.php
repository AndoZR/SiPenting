<?php

namespace App\Models;

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
}
