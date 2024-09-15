<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class akun_bidan extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'akun_bidan';

    protected $fillable = [
        'username',
        'nama',
        'password',
    ];
}
