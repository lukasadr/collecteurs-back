<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\ProduitSecondaire;
use Illuminate\Http\Request;

class ProduitSecondaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = ProduitSecondaire::with('images')->get();

        return response()->json($produits);
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
            'quantite' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'rendement' => 'required|numeric|min:0',
            'date_achat' => 'required|date',
            'date_traitement' => 'required|date',
            'date_expiration' => 'nullable|date',
            'categorie' => 'required|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $produit = ProduitSecondaire::create([
            'nom' => $validated['nom'],
            'quantite' => $validated['quantite'],
            'prix_achat' => $validated['prix_achat'],
            'prix_vente' => $validated['prix_vente'],
            'rendement' => $validated['rendement'],
            'date_achat' => $validated['date_achat'],
            'date_traitement' => $validated['date_traitement'],
            'date_expiration' => $validated['date_expiration'] ?? null,
            'categorie' => $validated['categorie'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = 'storage/' . $image->store('photos', 'public');
                $produit->images()->create(['image_url' => $path]);
            }
        }

        $produit->load('images');

        return response()->json($produit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produit = ProduitSecondaire::with('images')->findOrFail($id);

        return response()->json($produit);
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
        $produit = ProduitSecondaire::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'rendement' => 'required|numeric|min:0',
            'date_achat' => 'required|date',
            'date_traitement' => 'required|date',
            'date_expiration' => 'nullable|date',
            'categorie' => 'required|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $produit->update([
            'nom' => $validated['nom'],
            'quantite' => $validated['quantite'],
            'prix_achat' => $validated['prix_achat'],
            'prix_vente' => $validated['prix_vente'],
            'rendement' => $validated['rendement'],
            'date_achat' => $validated['date_achat'],
            'date_traitement' => $validated['date_traitement'],
            'date_expiration' => $validated['date_expiration'] ?? null,
            'categorie' => $validated['categorie'],
        ]);

        if ($request->hasFile('images')) {
            $produit->images()->delete();
            foreach ($request->file('images') as $image) {
                $path = 'storage/' . $image->store('photos', 'public');
                $produit->images()->create(['image_url' => $path]);
            }
        }

        return response()->json($produit, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produit = ProduitSecondaire::findOrFail($id);

        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès'],204);
    }

    public function indexMouvements()
    {
        $mouvements = MouvementStock::where('produit_type', ProduitSecondaire::class)->get();

        return response()->json($mouvements);
    }

    public function mouvementsProduit($id)
    {
        $produit = ProduitSecondaire::findOrFail($id);

        $mouvements = $produit->mouvements;

        return response()->json($mouvements);
    }
}
