<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class makanan extends Model
{
    use HasFactory;

    protected $table = 'makanan';

    protected $fillable = [
        'nama',
        'id_jenis_gizi',
    ];

    public function jenis_gizi()
    {
        return $this->belongsTo(jenis_gizi::class, 'id_jenis_gizi', 'id');
    }
}
