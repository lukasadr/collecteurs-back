<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "date", "etat"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produitsPrimaires()
    {
        return $this->belongsToMany(ProduitPrimaire::class);
    }

    public function produitsSecondaires()
    {
        return $this->belongsToMany(ProduitSecondaire::class);
    }

    public function getProduitsAttribute()
    {
        return $this->produitsPrimaires()->get()->merge($this->produitsSecondaires()->get());
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
