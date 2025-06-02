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

    // Relasi pivot sebagai model (optional)
    public function pivot_puskesmas_village()
    {
        return $this->hasMany(pivot_puskesmas_village::class, 'puskesmas_id');
    }

    // Relasi many-to-many villages langsung
    public function villages()
    {
        return $this->belongsToMany(villages::class, 'pivot_puskesmas_village', 'puskesmas_id', 'village_id')
                    ->withPivot('puskesmas_id', 'village_id'); // optional
    }

}
