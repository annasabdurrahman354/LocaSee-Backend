<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = 'kecamatans';

    protected $fillable = [
        'kabupaten_id',
        'title',
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }
}
