<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = User::where('role_id', 2)->get();

        return response()->json($clients);
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
            'email' => 'required|unique:users,email',
            'password' => 'required|string|min:8',
            'adresse' => 'required',
            'tel' => 'required|unique:users,tel',
            'societe' => 'nullable|string',
            'notes' => 'nullable|string',
            'solde' => 'nullable|numeric|min:0',
            'type' => 'string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nifstat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $photoPath = $request->hasFile('photo')
            ? 'storage/' . $request->file('photo')->store('photos', 'public')
            : '/';

        $user = User::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'role_id' => 2,
            'password' => Hash::make($validated['password']),
            'adresse' => $validated['adresse'],
            'tel' => $validated['tel'],
            'societe' => $validated['societe'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'solde' => $validated['solde'] ?? null,
            'type' => $validated['type'] ?? null,
            'nifstat' => $validated['nifstat'] ?? null,
            'photo' => $photoPath,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null
        ]);

        return response()->json(['user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, 200);
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
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8',
            'adresse' => 'required',
            'tel' => 'required|unique:users,tel,' . $user->id,
            'societe' => 'nullable|string',
            'notes' => 'nullable|string',
            'solde' => 'numeric|min:0',
            'type' => 'string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nifstat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $photoPath = $user->photo;

        if ($request->hasFile('photo'))
        {
            if ($user->photo && $user->photo !== '/') {
                Storage::disk('public')->delete(str_replace('storage/', '', $user->photo));
            }

            $photoPath = $request->file('photo')->store('photos', 'public');

            $photoPath = "storage/$photoPath";
        }

        $user->update(
        [
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'adresse' => $validated['adresse'],
            'tel' => $validated['tel'],
            'societe' => $validated['societe'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'solde' => $validated['solde'] ?? null,
            'type' => $validated['type'] ?? null,
            'photo' => $photoPath,
            'nifstat' => $validated['nifstat'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null
        ]);

        return response()->json(['message' => 'Utilisateur mis à jour', "user" => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->photo && $user->photo !== '/images/default.png') {
            Storage::disk('public')->delete(str_replace('storage/', '', $user->photo));
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès'],204);
    }

    public function indexAll()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function importUsers (Request $request) {
        $usersData = $request->all();

        foreach ($usersData as $userData) {
            $validator = Validator::make($userData, [
                'nom' => 'required|string|max:255',
                'email' => 'required|unique:users,email',
                'adresse' => 'required',
                'tel' => 'required|unique:users,tel',
                'societe' => 'nullable|string',
                'notes' => 'nullable|string',
                'solde' => 'nullable|numeric|min:0',
                'type' => 'string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'nifstat' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $userData['password'] = Hash::make('password');
            $userData['role_id'] = 2;

            User::create($userData);

            $users = User::where('role_id', 2)->get();
        }

        return response()->json($users, 201);
    }

}
