<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;

    protected $fillable = ["type", "date_emission", "date_finition"];

    public function pret()
    {
        return $this->belongsTo(Pret::class);
    }
}
