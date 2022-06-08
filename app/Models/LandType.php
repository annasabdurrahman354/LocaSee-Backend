<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandType extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'land_types';

    protected $fillable = [
        'title',
    ];
}
