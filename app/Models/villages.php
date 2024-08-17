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
        'id ',
        'district_id',
        'name',
    ];
}
