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
        Schema::create(table: 'fournisseurs', callback: function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->string("adresse");
            $table->string("email")->unique();
            $table->string("tel")->unique();
            $table->string("societe")->nullable();
            $table->string("nifstat");
            $table->unsignedInteger("delai_livraison");
            $table->text("notes")->nullable();
            $table->string("photo")->default("/");
            $table->decimal("latitude")->nullable();
            $table->decimal("longitude")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
