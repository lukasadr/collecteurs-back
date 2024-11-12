<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'fournisseur_produit_primaire', callback: function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("produit_primaire_id");
            $table->foreign("produit_primaire_id")->references("id")->on("produits_primaires")->onDelete("cascade");
            $table->unsignedBigInteger("fournisseur_id");
            $table->foreign("fournisseur_id")->references("id")->on("fournisseurs")->onDelete("cascade");
            $table->decimal("prix", 10, 2)->unsigned();
            $table->unsignedInteger("delai");
            $table->decimal("quantite", 10, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseur_produit_primaire');
    }
};
