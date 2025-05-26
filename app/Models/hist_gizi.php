<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hist_gizi extends Model
{
    use HasFactory;

    protected $table = 'hist_gizi';

    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nilai_gizi',
        'id_bayi'
    ];

    public function bayi()
    {
        return $this->belongsTo(bayi::class, 'id_bayi');
    }
}
