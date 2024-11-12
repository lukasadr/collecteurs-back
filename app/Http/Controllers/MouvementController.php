<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use Illuminate\Http\Request;

class MouvementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mouvements = MouvementStock::all();

        return response()->json($mouvements);
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
            'produit_id' => 'required|integer',
            'produit_type' => 'required|string|in:produit_primaire,produit_secondaire',
            'type' => 'required|string|in:entree,sortie',
            'date' => 'nullable|date',
            'quantite' => 'required|numeric|min:1',
            'source' => 'nullable|string|max:255'
        ]);

        try {
            $produit = null;
            if ($validated['produit_type'] === 'produit_primaire')
            {
                $produit = ProduitPrimaire::findOrFail($validated['produit_id']);
            }
            elseif ($validated['produit_type'] === 'produit_secondaire')
            {
                $produit = ProduitSecondaire::findOrFail($validated['produit_id']);
            }

            if($validated['type'] == 'entree')
            {
                $mouvement = MouvementStock::createEntry($produit, $validated['quantite'], $validated['source'], $validated['date']);
            }
            else
            {
                $mouvement = MouvementStock::createExit($produit, $validated['quantite'], $validated['source'], $validated['date']);
            }

            return response()->json(['message' => 'Crée avec succès','mouvement' => $mouvement], 201);

        }
        catch (\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mouvement = MouvementStock::with('produit')->findOrFail($id);

        return response()->json($mouvement);
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
        $mouvement = MouvementStock::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|in:entree,sortie',
            'date' => 'nullable|date',
            'quantite' => 'required|numeric|min:1',
            'source' => 'nullable|string|max:255'
        ]);

        $produit = $mouvement->produit;

        if($validated['type'] == 'entree')
        {
            $produit->quantite += $validated['quantite'] - $mouvement->quantite;
        }
        else
        {
            $produit->quantite -= $validated['quantite'] - $mouvement->quantite;
        }

        $mouvement->update([
            'type' => 'entree',
            'quantite' => $validated['quantite'],
            'source' => $validated['source'],
            'produit_id' => $produit->id,
            'date' => $validated['date'],
            'produit_type' => get_class($produit)
        ]);

        $produit->save();


        return response()->json(['message' => 'Modifié avec succès','mouvement' => $mouvement], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mouvement = MouvementStock::findOrFail($id);

        $produit = $mouvement->produit;

        if($mouvement->type == "entree")
        {
            if($produit->quantite < $mouvement->quantite)
            {
                throw new \Exception("Stock insuffisant pour cette opération.");
            }

            $produit->quantite -= $mouvement->quantite;
        }
        else
        {
            $produit->quantite += $mouvement->quantite;
        }

        $produit->save();

        $mouvement->delete();

        return response()->json(['message' => 'Mouvement supprimé avec succès'],204);
    }
}
