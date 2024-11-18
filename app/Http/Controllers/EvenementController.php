<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenements = Evenement::all();

        return response()->json($evenements);
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
            'description' => 'nullable|string',
            'debut' => 'required|date_format:Y-m-d H:i:s',
            'fin' => 'required|date_format:Y-m-d H:i:s|after_or_equal:debut',
        ]);        

        $evenement = Evenement::create($validated);

        return response()->json($evenement, 201);
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
        $evenement = Evenement::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'debut' => 'required|date_format:Y-m-d H:i:s',
            'fin' => 'required|date_format:Y-m-d H:i:s|after_or_equal:debut',
        ]);   

        $evenement->update($validated);

        return response()->json($evenement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evenement = Evenement::findOrFail($id);

        $evenement->delete();

        return response()->noContent();
    }
}
