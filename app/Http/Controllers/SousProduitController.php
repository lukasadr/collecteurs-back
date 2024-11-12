<?php

namespace App\Http\Controllers;

use App\Models\SousProduit;
use Illuminate\Http\Request;

class SousProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sous_produits = SousProduit::all();

        return response()->json($sous_produits);
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
            'nom' => 'required|string|max:255',
            'produit_secondaire_id' => 'required|integer|min:0',
            'quantite' => 'required|integer|min:0',
        ]);

        $sous_produit = SousProduit::create([
            'nom' => $validated['nom'],
            'produit_secondaire_id' => $validated['produit_secondaire_id'],
            'quantite' => $validated['quantite'],
        ]);

        return response()->json($sous_produit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sous_produit = SousProduit::findOrFail($id);

        return response()->json($sous_produit);
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
        $sous_produit = SousProduit::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'produit_secondaire_id' => 'required|integer|min:0',
            'quantite' => 'required|integer|min:0',
        ]);

        $sous_produit->update([
            'nom' => $validated['nom'],
            'produit_secondaire_id' => $validated['produit_secondaire_id'],
            'quantite' => $validated['quantite'],
        ]);

        return response()->json(['message' => 'Sous-produit mis à jour']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sous_produit = SousProduit::findOrFail($id);

        $sous_produit->delete();

        return response()->json(['message' => 'Sous-produit supprimé'], 204);
    }
}
