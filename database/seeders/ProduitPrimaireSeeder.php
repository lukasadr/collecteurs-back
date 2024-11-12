<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ProduitPrimaire;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class ProduitPrimaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProduitPrimaire::create([
            'nom' => 'Blé',
            'quantite' => 100,
            'prix_achat' => 50.00,
            'prix_vente' => 70.00,
            'date_achat' => '2024-01-01',
            'date_recolte' => '2024-01-15',
            'categorie' => 'Alimentaire',
            'origine' => 'Origine 1',
            'duree_conservation' => 30,
        ]);

        ProduitPrimaire::create([
            'nom' => 'Farine de blé',
            'quantite' => 200,
            'prix_achat' => 40.00,
            'prix_vente' => 60.00,
            'date_achat' => '2024-02-01',
            'date_recolte' => '2024-02-15',
            'categorie' => 'Industriel',
            'origine' => 'Origine 2',
            'duree_conservation' => 40,
        ]);

        ProduitPrimaire::create([
            'nom' => 'Cacarotte',
            'quantite' => 300,
            'prix_achat' => 30.00,
            'prix_vente' => 50.00,
            'date_achat' => '2024-03-01',
            'date_recolte' => '2024-03-15',
            'categorie' => 'Alimentaire',
            'origine' => 'Origine 3',
            'duree_conservation' => 50,
        ]);

        $fournisseur = Fournisseur::first();
        $produitsPrimaires = ProduitPrimaire::all();

        foreach ($produitsPrimaires as $produitPrimaire) {
            $fournisseur->produitsPrimaires()->attach($produitPrimaire->id, [
                'prix' => $produitPrimaire->prix_achat,
                'delai' => $fournisseur->delai_livraison,
                'quantite' => $produitPrimaire->quantite,
            ]);
        }
    }
}
