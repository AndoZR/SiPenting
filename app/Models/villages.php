<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class villages extends Model
{
    use HasFactory;

    protected $table = 'villages';

    public $timestamps = false;

    protected $fillable= [
        'id',
        'district_id',
        'name',
    ];


    // Relasi Village ke User
    public function users()
    {
        return $this->hasMany(User::class, 'id_villages');  // 'village_id' adalah foreign key di tabel users
    }

    public function district()
    {
        return $this->belongsTo(districts::class, 'district_id');
    }

    public function puskesmas()
    {
        return $this->belongsToMany(akun_puskesmas::class, 'pivot_puskesmas_village', 'village_id', 'puskesmas_id');
    }


}
