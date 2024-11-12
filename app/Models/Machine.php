<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = ["etat", "vitesse", "description", "produits_compatibles", "tarif", "photo"];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function getPhotoUrlAttribute()
    {
        return asset($this->photo);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
