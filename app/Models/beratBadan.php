<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class beratBadan extends Model
{
    use HasFactory;

    protected $table = 'beratBadan';

    protected $fillable = [
        'bbNow',
        'id_users'
    ];
}
