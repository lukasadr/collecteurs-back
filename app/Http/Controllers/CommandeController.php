<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Contrat;
use App\Models\MouvementStock;
use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use App\Models\User;
use App\Notifications\ValidationCommande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::all();

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
            'user_id' => 'required|string|max:255',
            'etat' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $commande = Commande::create([
            'user_id' => $validated['user_id'],
            'etat' => $validated['etat'],
            'date' => $validated['date'],
        ]);

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
            'user_id' => 'required|string|max:255',
            'etat' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $commande->update([
            'user_id' => $validated['user_id'],
            'etat' => $validated['etat'],
            'date' => $validated['date'],
        ]);

        return response()->json(['Commande mise à jour']);
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
            'ventes' => 0,
            'entrees' => 0,
            'sorties' => 0,
            'contrats' => 0
        ];

        $dashboardData['ventes'] = Commande::where('etat', 'paye')->count();
        $dashboardData['entrees'] = MouvementStock::entrees()->count();
        $dashboardData['sorties'] = MouvementStock::sorties()->count();
        $dashboardData['contrats'] = Contrat::count();

        return response()->json($dashboardData);
    }
}
