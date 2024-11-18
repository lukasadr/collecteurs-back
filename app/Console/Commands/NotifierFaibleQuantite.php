<?php

namespace App\Console\Commands;

use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use App\Models\User;
use App\Notifications\AlerteStock;
use Illuminate\Console\Command;

class NotifierFaibleQuantite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notifier-faible-quantite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifier l\'administrateur de la quantité faible du produit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $produitPrimaire = ProduitPrimaire::where('quantite', '<=', 250)->first();
        $produitSecondaire = ProduitSecondaire::where('quantite', '<=', 250)->first();

        if ($produitPrimaire || $produitSecondaire) {
            $admins = User::whereDoesntHave('notifications', function ($query) {
                $query->where('type', AlerteStock::class);
            })->where('role_id', 1)->get();

            foreach($admins as $admin)
            {
                $admin->notify(new AlerteStock());
            }
        }
    }
}
