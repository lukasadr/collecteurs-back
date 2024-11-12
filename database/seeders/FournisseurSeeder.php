<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fournisseur::create([
            'nom' => 'Marie de Marie',
            'adresse' => 'Adresse 1',
            'email' => 'fournisseur@example.com',
            'tel' => '1234567890',
            'societe' => 'Societe 1',
            'nifstat' => 'NIF1',
            'delai_livraison' => 5,
            'notes' => 'Notes for Fournisseur 1',
            'photo' => '/',
        ]);
    }
}
