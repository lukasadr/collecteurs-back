<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\MouvementStock;
use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use App\Models\User;
use App\Notifications\ValidationCommande;
use Illuminate\Http\Request;
use phpseclib3\Crypt\TripleDES;
use DateTime;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::with('user')->get();

        return response()->json($commandes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'etat' => 'required|string|max:255',
            'date' => 'required|date',
            'products' => 'required|array',
        ]);

        $commande = Commande::create([
            'user_id' => $validated['user_id'],
            'etat' => $validated['etat'],
            'date' => $validated['date'],
        ]);

        foreach ($validated['products'] as $produit)
        {
            if ($produit['product_type'] == 'produit_primaire') {
                $commande->produitsPrimaires()->attach($produit['product_id'], ['quantite' => $produit['quantity']]);
                if($validated['etat'] == 'Payé')
                {
                    MouvementStock::createExit(
                        produit: ProduitPrimaire::findOrFail($produit['product_id']),  
                        quantite: $produit['quantity'], 
                        source: "Client",  
                        date: $validated['date']
                    );
                }
            } else {
                $commande->produitsSecondaires()->attach($produit['product_id'], ['quantite' => $produit['quantity']]);
                if($validated['etat'] == 'Payé')
                {
                    MouvementStock::createExit(
                        produit: ProduitSecondaire::findOrFail($produit['product_id']),  
                        quantite: $produit['quantity'], 
                        source: "Client",
                        date: $validated['date']
                    );
                }
            }
        }

        $commande->load('user');

        return response()->json($commande, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commande = Commande::findOrFail($id);

        return response()->json($commande);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $commande = Commande::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'etat' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $commande->update([
            'user_id' => $validated['user_id'],
            'etat' => $validated['etat'],
            'date' => $validated['date'],
        ]);

        $commande->load('user');

        return response()->json($commande);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commande = Commande::findOrFail($id);

        $commande->delete();

        return response()->json(['message' => 'Commande supprimée'], 204);
    }

    public function valider(string $id)
    {
        $commande = Commande::findOrFail($id);

        $commande->etat = "en attente";

        $commande->save();

        $admins = User::where('role_id', 1)->get();
        foreach($admins as $admin)
        {
            $admin->notify(new ValidationCommande());
        }

        return response()->json($commande);
    }

    public function dashboard()
    {
        $dashboardData = [
            'ventes' => Commande::where('etat', 'Payé')->count(),
            'entrees' => MouvementStock::entrees()->count(),
            'sorties' => MouvementStock::sorties()->count(),
            'recette' => 0,
            'lastSixMonthsVentes' => array_fill(0, 6, 0)
        ];

        $commandesPaye = Commande::where('etat', 'Payé')->whereMonth('date', now()->month)->get();
        foreach($commandesPaye as $commande)
        {
            $dashboardData['recette'] += $commande->montant;
        }

        for ($i = 0; $i < 6; $i++) {
            $month = now()->subMonths($i)->month;
            $dashboardData['lastSixMonthsVentes'][$i] = Commande::where('etat', 'Payé')->whereMonth('date', $month)->count();
        }

        return response()->json($dashboardData);
    }

    public function validerPanier(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'produits' => 'required|array',
        ]);

        $commande = Commande::create([
            'user_id' => $validated['user_id'],
            'etat' => 'Payé',
            'date' => now(),
        ]);

        foreach ($validated['produits'] as $produit)
        {
            if ($produit['produit_type'] == 'produit_primaire') {
                $commande->produitsPrimaires()->attach($produit['produit_id'], ['quantite' => $produit['quantity']]);
                MouvementStock::createExit(
                    produit: ProduitPrimaire::findOrFail($produit['produit_id']),  
                    quantite: $produit['quantity'], 
                    source: "Client",  
                    date: now()
                );
            } else {
                $commande->produitsSecondaires()->attach($produit['produit_id'], ['quantite' => $produit['quantity']]);
                MouvementStock::createExit(
                    produit: ProduitSecondaire::findOrFail($produit['produit_id']),  
                    quantite: $produit['quantity'], 
                    source: "Client",
                    date: now()
                );
            }
        }

        $admins = User::where('role_id', 1)->get();
        foreach($admins as $admin)
        {
            $admin->notify(new ValidationCommande());
        }

        return response()->json($commande);
    }

    public function commandeUser($id)
    {
        $commandes = Commande::where('user_id', $id)->orderByDesc('date')->get();

        return response()->json($commandes);
    }
}
