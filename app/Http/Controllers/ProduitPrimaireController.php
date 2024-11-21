<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use Illuminate\Http\Request;

class ProduitPrimaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = ProduitPrimaire::with('images')->get();

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
            'date_achat' => 'required|date',
            'date_recolte' => 'required|date',
            'categorie' => 'required|string|max:255',
            'origine' => 'nullable|string|max:255',
            'duree_conservation' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $produit = ProduitPrimaire::create([
            'nom' => $validated['nom'],
            'quantite' => $validated['quantite'],
            'prix_achat' => $validated['prix_achat'],
            'prix_vente' => $validated['prix_vente'],
            'date_achat' => $validated['date_achat'],
            'date_recolte' => $validated['date_recolte'],
            'categorie' => $validated['categorie'],
            'origine' => $validated['origine'] ?? null,
            'duree_conservation' => $validated['duree_conservation'] ?? null,
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
        $produit = ProduitPrimaire::with('images')->findOrFail($id);

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
        $produit = ProduitPrimaire::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'date_achat' => 'required|date',
            'date_recolte' => 'required|date',
            'categorie' => 'required|string|max:255',
            'origine' => 'nullable|string|max:255',
            'duree_conservation' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $produit->update([
            'nom' => $validated['nom'],
            'quantite' => $validated['quantite'],
            'prix_achat' => $validated['prix_achat'],
            'prix_vente' => $validated['prix_vente'],
            'date_achat' => $validated['date_achat'],
            'date_recolte' => $validated['date_recolte'],
            'categorie' => $validated['categorie'],
            'origine' => $validated['origine'] ?? null,
            'duree_conservation' => $validated['duree_conservation'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            $produit->images()->delete();
            foreach ($request->file('images') as $image) {
                $path = 'storage/' . $image->store('photos', 'public');
                $produit->images()->create(['image_url' => $path]);
            }
        }

        $produit->load('images');

        return response()->json($produit, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produit = ProduitPrimaire::findOrFail($id);

        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès'],204);
    }

    public function traiter(Request $request, int $id)
    {
        $produit = ProduitPrimaire::findOrFail($id);

        $validated = $request->validate([
            "quantite" => "required|numeric|min:1",
            "rendement" => "required|numeric|min:0|max:1",
            "nom" => "required|string|max:255",
            "prix_achat" => "required|numeric|min:0",
            "prix_vente" => "required|numeric|min:0",
            "categorie" => "required|string|max:255",
            "date_expiration" => "nullable|date",
            "date_traitement" => "required|date",
        ]);

        if($produit->quantite < $validated['quantite'])
        {
            return response()->json(['message' => 'Quantité insuffisante']);
        }

        $produit->quantite -= $validated['quantite'];
        $produit->save();

        $produitSecondaire = ProduitSecondaire::create([
            'nom' => $validated['nom'],
            'quantite' => $validated['quantite'] * $validated["rendement"],
            'prix_achat' => $validated['prix_achat'],
            'prix_vente' => $validated['prix_vente'],
            'rendement' => $validated['rendement'],
            'date_achat' => null,
            'date_traitement' => $validated['date_traitement'],
            'date_expiration' => $validated['date_expiration'],
            'categorie' => $validated['categorie'],
        ]);

        return response()->json(['message' => 'Produit traité avec succès'], $produitSecondaire);
    }

    public function indexMouvements()
    {
        $mouvements = MouvementStock::where('produit_type', ProduitPrimaire::class)->get();

        return response()->json($mouvements);
    }

    public function mouvementsProduit($id)
    {
        $produit = ProduitPrimaire::findOrFail($id);

        $mouvements = $produit->mouvements;

        return response()->json($mouvements);
    }

    public function categorieProduit($categorie)
    {
        if(in_array(needle: $categorie, haystack: ['alimentaire', 'industriel', 'elevage']))
        {
            $produits = ProduitPrimaire::with(relations: 'images')->where(column: 'categorie', operator: ucfirst(string: $categorie))->get();
        }
        else
        {
            return response()->json(data: ['message' => 'Catégorie non trouvée'], status: 404);
        }

        return response()->json(data: $produits);
    }
}
