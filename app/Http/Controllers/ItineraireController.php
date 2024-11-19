<?php

namespace App\Http\Controllers;

use App\Models\Itineraire;
use Illuminate\Http\Request;

class ItineraireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itineraires = Itineraire::all();

        return response()->json($itineraires);
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
            'distance' => 'required|numeric|min:0',
            'duree' => 'required|numeric|min:0',
            'coordonees' => 'required|json'
        ]);

        $itineraire = Itineraire::create($validated);

        return response()->json($itineraire);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $itineraire = Itineraire::findOrFail($id);

        $itineraire->delete();

        return response()->noContent();
    }
}
