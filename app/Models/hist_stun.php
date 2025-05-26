<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hist_stun extends Model
{
    use HasFactory;

    protected $table = 'hist_stun';

    public $timestamps = false;

    protected $fillable= [
        'jenis',
        'tanggal',
        'id_bayi'
    ];
}
