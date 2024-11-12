<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\MouvementStock;
use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fournisseurs = Fournisseur::all();

        return response()->json($fournisseurs);
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
            'email' => 'required|email|unique:fournisseurs,email',
            'adresse' => 'required|string|max:255',
            'tel' => 'required|unique:fournisseurs,tel',
            'delai_livraison' => 'required|integer|min:0',
            'nifstat' => 'required|string|max:255',
            'societe' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public')
        : '/images/default.png';

        $fournisseur = Fournisseur::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'adresse' => $validated['adresse'],
            'tel' => $validated['tel'],
            'societe' => $validated['societe'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'delai_livraison' => $validated['delai_livraison'],
            'nifstat' => $validated['nifstat'],
            'photo' => $photoPath,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return response()->json($fournisseur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        return response()->json($fournisseur);
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
        $fournisseur = Fournisseur::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|unique:fournisseurs,email,' . $fournisseur->id,
            'adresse' => 'required',
            'tel' => 'required|unique:fournisseurs,tel,' . $fournisseur->id,
            'delai_livraison' => 'required|integer|min:0',
            'nifstat' => 'required|string|max:255',
            'societe' => 'nullable|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public')
        : $fournisseur->photo;

        $fournisseur->update([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'adresse' => $validated['adresse'],
            'tel' => $validated['tel'],
            'societe' => $validated['societe'],
            'notes' => $validated['notes'],
            'delai_livraison' => $validated['delai_livraison'],
            'nifstat' => $validated['nifstat'],
            'photo' => $photoPath,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return response()->json(['message' => 'Fournisseur mis à jour', 'fournisseur' => $fournisseur]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        $fournisseur->delete();

        return response()->json(['message' => 'Fournisseur supprimé avec succès'],204);
    }

    public function fournir(Request $request)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|integer|exists:fournisseurs,id',
            'produit_id' => 'required|integer',
            'produit_type' => 'required|string|in:produit_primaire,produit_secondaire',
            'prix' => 'required|numeric|min:0',
            'delai' => 'required|integer|min:0',
            'quantite' => 'required|numeric|min:1',
            'date' => 'nullable|date'
        ]);

        $produit = $validated['produit_type'] === 'produit_primaire'
            ? ProduitPrimaire::findOrFail($validated['produit_id'])
            : ProduitSecondaire::findOrFail($validated['produit_id']);

        $fournisseur = Fournisseur::findOrFail($validated['fournisseur_id']);

        $produit->fournisseurs()->attach($validated['fournisseur_id'], [
            'prix' => $validated['prix'],
            'delai' => $validated['delai'],
            'quantite' => $validated['quantite'],
        ]);

        MouvementStock::createEntry(
            produit: $produit,
            quantite: $validated['quantite'],
            source: "Fournisseur: {$fournisseur->nom}",
            date: $validated['date']
        );

        return response()->json(["message" => "Fourniture créée avec succès"], 201);
    }

}
