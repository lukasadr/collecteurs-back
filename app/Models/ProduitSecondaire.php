<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitSecondaire extends Model
{
    use HasFactory;

    protected $table = "produits_secondaires";

    protected $fillable = ["nom", "quantite", "rendement", "prix_achat", "prix_vente", "date_achat", "date_traitement", "date_expiration", "categorie"];

    protected $appends = ["statut", "type"];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($product) {
            $product->images()->delete();
            $product->mouvements()->delete();
        });
    }

    public function fournisseurs()
    {
        return $this->belongsToMany(Fournisseur::class);
    }

    public function sousProduit()
    {
        return $this->hasOne(SousProduit::class);
    }

    public function mouvements()
    {
        return $this->morphMany(MouvementStock::class, "produit");
    }

    public function entrees()
    {
        return $this->morphMany(MouvementStock::class, 'produit')
            ->where('type', 'entree');
    }

    public function sorties()
    {
        return $this->morphMany(MouvementStock::class, 'produit')
            ->where('type', 'sortie');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getStatutAttribute()
    {
        if($this->quantite <= 250)
        {
            return "Critique";
        }
        elseif($this->quantite <= 500)
        {
            return "Faible";
        }
        else
        {
            return "Normal";
        }
    }

    public function getTypeAttribute()
    {
        return "Secondaire";
    }
}
