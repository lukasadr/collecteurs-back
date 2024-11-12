<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MouvementStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch some existing produits to reference
        $produitsPrimaires = \App\Models\ProduitPrimaire::all();
        $produitsSecondaires = \App\Models\ProduitSecondaire::all();

        // Combine both products
        $produits = $produitsPrimaires->concat($produitsSecondaires);

        foreach ($produits as $produit) {
            // Create a random number of movements for each product (e.g., between 3 and 5)
            $numberOfMovements = rand(3, 5);

            for ($i = 0; $i < $numberOfMovements; $i++) {
                $type = ['entree', 'sortie'][array_rand(['entree', 'sortie'])];
                DB::table('mouvements_stocks')->insert([
                    'produit_id' => $produit->id,
                    'produit_type' => get_class($produit),
                    'type' => $type,
                    'quantite' => rand(1, 100), // Random quantity between 1 and 100
                    'source' => 'Supplier XYZ', // Example source
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
