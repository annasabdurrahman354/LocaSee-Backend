<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'price',
        'area',
        'address',
        'latitude',
        'longitude',
        'images',
        'land_type_id',
        'user_id',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function landType()
    {
        return $this->belongsTo(LandType::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function dateDiff(){
        Carbon::setLocale('id');
        return $this->attributes['created_at']->diffForHumans(); 
    }
}
