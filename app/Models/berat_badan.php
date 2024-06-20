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
        'id_users'
    ];
}
