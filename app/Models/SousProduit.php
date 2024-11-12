<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousProduit extends Model
{
    use HasFactory;

    protected $fillable = ["nom, produit_secondaire_id, quantite"];

    public function produit()
    {
        return $this->belongsTo(ProduitSecondaire::class);
    }
}
