<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_gizi extends Model
{
    use HasFactory;

    protected $table = 'jenis_gizi';

    public function makanan()
    {
        return $this->hasMany(makanan::class, 'id_jenis_gizi', 'id');
    }
}
