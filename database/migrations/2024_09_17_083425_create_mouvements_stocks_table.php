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
        Schema::create(table: 'mouvements_stocks', callback: function (Blueprint $table) {
            $table->id();
            $table->enum(column: 'type', allowed: ['entree', 'sortie']);
            $table->morphs('produit');
            $table->decimal('quantite', 8, 2);
            $table->date('date')->default(now());
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stocks');
    }
};
