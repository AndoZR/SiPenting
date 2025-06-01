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
        'name',
        'nomor',
        'id_district',
        'password',
    ];

    public function districts() {
        return $this->belongsTo(districts::class, 'id_district');
    }

    public function pivot_puskesmas_village() {
        return $this->hasMany(pivot_puskesmas_village::class, 'id_district');
    }

    public function villages()
    {
        return $this->belongsToMany(Villages::class, 'pivot_puskesmas_village', 'puskesmas_id', 'village_id');
    }

}
