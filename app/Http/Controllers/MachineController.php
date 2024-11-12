<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machines = Machine::all();

        return response()->json($machines);
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
            'etat' => 'required|string|max:255',
            'vitesse' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'produits_compatibles' => 'required|string',
            'tarif' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public')
        : '/images/default.png';

        $machine = Machine::create([
            'etat' => $validated['etat'],
            'vitesse' => $validated['vitesse'],
            'desription' => $validated['prix_achat'],
            'produits_compatibles' => $validated['produits_compatibles'],
            'tarif' => $validated['tarif'],
            'photo' => $photoPath,
        ]);

        return response()->json($machine, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $machine = Machine::findOrFail($id);

        return response()->json($machine);
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
        $machine = Machine::findOrFail($id);

        $validated = $request->validate([
            'etat' => 'required|string|max:255',
            'vitesse' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'produits_compatibles' => 'required|string',
            'tarif' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public')
        : '/images/default.png';

        $machine->update([
            'etat' => $validated['etat'],
            'vitesse' => $validated['vitesse'],
            'desription' => $validated['prix_achat'],
            'produits_compatibles' => $validated['produits_compatibles'],
            'tarif' => $validated['tarif'],
            'photo' => $photoPath,
        ]);

        return response()->json(['message' => 'Machine mise à jour']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $machine = Machine::findOrFail($id);

        $machine->delete();

        return response()->json(['message' => 'Machine supprimée avec succès'],204);
    }
}
