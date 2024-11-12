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
        Schema::create(table: 'machines', callback: function (Blueprint $table) {
            $table->id();
            $table->string("etat");
            $table->decimal("vitesse", 8, 2)->unsigned();
            $table->text("description")->nullable();
            $table->string("produits_compatibles");
            $table->double("tarif")->unsigned();
            $table->string("photo")->default("/");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
