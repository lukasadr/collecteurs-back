<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'image_url'
    ];

    protected $appends = ['url'];

    public function produit()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return asset($this->image_url);
    }
}
