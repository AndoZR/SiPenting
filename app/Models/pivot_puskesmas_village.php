<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pivot_puskesmas_village extends Model
{
    use HasFactory;

    protected $table = 'pivot_puskesmas_village';

    public $timestamps = false;

    protected $fillable= [
        'puskesmas_id',
        'village_id'
    ];
    

    // app/Models/Puskesmas.php
    public function villages()
    {
        return $this->belongsToMany(villages::class, 'pivot_puskesmas_village', 'puskesmas_id', 'village_id');
    }

    // app/Models/Village.php
    public function puskesmas()
    {
        return $this->belongsToMany(akun_puskesmas::class, 'pivot_puskesmas_village', 'village_id', 'puskesmas_id');
    }
}
