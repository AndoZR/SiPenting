<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class districts extends Model
{
    use HasFactory;

    protected $table = 'districts';

    public $timestamps = false;

    protected $fillable= [
        'id',
        'regency_id ',
        'name',
    ];
}
