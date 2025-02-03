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
        $primary = $this->produitsPrimaires()->get()->map(function ($item) {
            $item->source = 'primary';
            return $item;
        });

        $secondary = $this->produitsSecondaires()->get()->map(function ($item) {
            $item->source = 'secondary';
            return $item;
        });

        $combined = $primary->concat($secondary)->groupBy(function ($item) {
            return $item->id . $item->source;
        });

        $summed = $combined->map(function ($group) {
            $first = $group->first();
            $first->pivot->quantite = $group->sum(function ($item) {
                return $item->pivot->quantite;
            });
            return $first;
        });

        return $summed->values()->all(); // Convert the collection to an array
    }



    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
