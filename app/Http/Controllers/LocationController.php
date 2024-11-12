<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Machine;
use App\Models\User;
use App\Notifications\DemandeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();

        return response()->json($locations);
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
            'machine_id' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date'
        ]);

        $location = Location::create([
            'user_id' => Auth::id(),
            'machine_id' => $validated['machine_id'],
            'etat' => 'en attente',
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin']
        ]);

        $admins = User::where('role_id', 1)->get();
        $machine = Machine::findOrFail($validated['machine_id']);

        foreach($admins as $admin)
        {
            $admin->notify(new DemandeLocation(Auth::user(), $machine));
        }

        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::findOrFail($id);

        return response()->json($location);
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
        $location = Location::findOrFail($id);

        $validated = $request->validate([
            'machine_id' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date'
        ]);

        $location->update([
            'machine_id' => $validated['machine_id'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin']
        ]);

        return response()->json($location, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);

        $location->delete();

        return response()->json(['message' => 'Location supprimé avec succès'], 204);
    }
}
