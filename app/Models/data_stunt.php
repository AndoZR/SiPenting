<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_stunt extends Model
{
    use HasFactory;

    protected $table = 'data_stunt';

    protected $fillable = [
        'Umur (bulan)', 'Panjang Badan (cm) -3 SD', 'Panjang Badan (cm) -2 SD', 'Panjang Badan (cm) -1 SD', 'Panjang Badan (cm) Median', 'Panjang Badan (cm) +1 SD', 'Panjang Badan (cm) +2 SD', 'Panjang Badan (cm) +3 SD', 'kelamin'
    ];
}
