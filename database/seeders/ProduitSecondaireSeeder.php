<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ProduitSecondaire;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class ProduitSecondaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProduitSecondaire::create([
            'nom' => 'Riz local',
            'quantite' => 150,
            'prix_achat' => 70.00,
            'prix_vente' => 90.00,
            'categorie' => 'Alimentaire',
            'date_achat' => '2024-01-01',
            'date_traitement' => '2024-01-10',
            'date_expiration' => '2024-06-01',
            'rendement' => 0.9
        ]);

        ProduitSecondaire::create([
            'nom' => 'Riz pas local',
            'quantite' => 250,
            'prix_achat' => 60.00,
            'prix_vente' => 80.00,
            'categorie' => 'Alimentaire',
            'date_achat' => '2024-02-01',
            'date_traitement' => '2024-02-10',
            'date_expiration' => '2024-07-01',
            'rendement' => 0.7
        ]);

        ProduitSecondaire::create([
            'nom' => 'Vegetal',
            'quantite' => 350,
            'prix_achat' => 50.00,
            'prix_vente' => 70.00,
            'categorie' => 'Alimentaire',
            'date_achat' => '2024-03-01',
            'date_traitement' => '2024-03-10',
            'date_expiration' => '2024-08-01',
            'rendement' => 0.5
        ]);

        $fournisseur = Fournisseur::first(); // or use a specific ID
        $produitsSecondaires = ProduitSecondaire::all();

        foreach ($produitsSecondaires as $produitSecondaire) {
            $fournisseur->produitsSecondaires()->attach($produitSecondaire->id, [
                'prix' => $produitSecondaire->prix_achat,
                'delai' => $fournisseur->delai_livraison,
                'quantite' => $produitSecondaire->quantite,
            ]);
        }
    }
}
