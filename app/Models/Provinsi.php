<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'provinsis';

    protected $fillable = [
        'title',
    ];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class);
    }
}
