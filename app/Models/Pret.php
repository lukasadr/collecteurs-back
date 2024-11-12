<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pret extends Model
{
    use HasFactory;

    protected $fillable = ["montat", "date_debut", "date_fin", "taux_interet", "statut"];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function contrat()
    {
        return $this->hasOne(Contrat::class);
    }
}
