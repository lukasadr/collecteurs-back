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
        Schema::create('sous_produits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("produit_secondaire_id");
            $table->foreign("produit_secondaire_id")->references("id")->on("produits_secondaires")->onDelete("cascade");
            $table->string("nom");
            $table->decimal("quantite", 8, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_produits');
    }
};
