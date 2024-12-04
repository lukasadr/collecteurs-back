<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "date", "etat"];

    protected $appends = ['montant', 'produits'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produitsPrimaires()
    {
        return $this->belongsToMany(ProduitPrimaire::class)->withPivot('quantite');
    }

    public function produitsSecondaires()
    {
        return $this->belongsToMany(ProduitSecondaire::class)->withPivot('quantite');
    }

    public function getMontantAttribute()
    {
        $montant = 0;
        $produitsPrimaires = $this->produitsPrimaires;
        $produitSecondaires = $this->produitsSecondaires;

        foreach($produitsPrimaires as $produitPrimaire)
        {
            $montant += $produitPrimaire->prix_vente*$produitPrimaire->pivot->quantite;
        }

        foreach($produitSecondaires as $produitSecondaire)
        {
            $montant += $produitSecondaire->prix_vente*$produitSecondaire->pivot->quantite;
        }

        return $montant;
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
