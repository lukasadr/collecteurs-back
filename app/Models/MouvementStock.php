<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = "mouvements_stocks";

    protected $fillable = ["type", "quantite", "source", "date","produit_id", "produit_type"];

    protected $appends = ["nom_produit"];

    public function produit()
    {
        return $this->morphTo();
    }

    public static function createEntry($produit, $quantite, $source = null, $date)
    {
        $produit->quantite += $quantite;
        $produit->save();

        return self::create([
            'type' => 'entree',
            'quantite' => $quantite,
            'source' => $source,
            'produit_id' => $produit->id,
            'date' => $date,
            'produit_type' => get_class($produit)
        ]);
    }

    public static function createExit($produit, $quantite, $source = null, $date)
    {
        if($produit->quantite < $quantite)
        {
            throw new \Exception('Stock insuffisant pour cette opération.');
        }

        $produit->quantite -= $quantite;
        $produit->save();

        return self::create([
            'type' => 'sortie',
            'quantite' => $quantite,
            'source' => $source,
            'produit_id' => $produit->id,
            'date' => $date,
            'produit_type' => get_class($produit)
        ]);
    }

    public function getNomProduitAttribute()
    {
        return $this->produit->nom;
    }

    public function scopeEntrees($query)
    {
        return $query->where('type', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type', 'sortie');
    }
}
