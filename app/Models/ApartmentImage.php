<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentImage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['apartment_id', 'image_url'];

    public function images()
    {
        return $this->hasMany(ApartmentImage::class, 'apartment_id');
    }

}
