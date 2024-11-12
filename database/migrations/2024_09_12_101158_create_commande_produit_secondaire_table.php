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
        Schema::create('commande_produit_secondaire', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("commande_id");
            $table->foreign("commande_id")->references("id")->on("commandes")->onDelete("cascade");
            $table->unsignedBigInteger("produit_secondaire_id");
            $table->foreign("produit_secondaire_id")->references("id")->on("produits_secondaires")->onDelete("cascade");
            $table->unsignedInteger("quantite");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_produit_secondaire');
    }
};
