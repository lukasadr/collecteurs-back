<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = ["nom", "email", "nifstat", "adresse", "tel", "societe", "delai_livraison", "notes", "photo", "latitude", "longitude"];

    protected $appends = ["photo_url", "produits"];

    public function getPhotoUrlAttribute()
    {
        return asset("storage/$this->photo");
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
        $produitsPrimaires = $this->produitsPrimaires->map(function($produit) {
            $produit->source = 'primary';
            return $produit->load('images');
        });

        $produitsSecondaires = $this->produitsSecondaires->map(function($produit) {
            $produit->source = 'secondary';
            return $produit->load('images');
        });

        return $produitsPrimaires->concat($produitsSecondaires)->unique(function ($item) {
            return $item->id . $item->source;
        });
    }

}
