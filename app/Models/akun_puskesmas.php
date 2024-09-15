<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class akun_puskesmas extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'akun_puskesmas';

    protected $fillable = [
        'username',
        'nama',
        'password',
    ];
}
