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
        Schema::create(table: 'produits_secondaires', callback: function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->decimal("quantite", 8, 2)->unsigned();
            $table->decimal("prix_achat", 10, 2)->unsigned()->default(0);
            $table->decimal("prix_vente", 10, 2)->unsigned();
            $table->decimal("rendement", 5, 2)->unsigned();
            $table->string("categorie");
            $table->date("date_achat")->nullable();
            $table->date("date_traitement");
            $table->date("date_expiration")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits_secondaires');
    }
};
