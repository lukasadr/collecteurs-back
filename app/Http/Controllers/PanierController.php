<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\ProduitPrimaire;
use App\Models\ProduitSecondaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PanierController extends Controller
{
    public function getCart()
    {
        $cart = session()->get('cart', []);
        return response()->json($cart);
    }


    public function ajouter(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|integer',
            'produit_type' => 'required|string|in:produit_primaire,produit_secondaire',
            'quantite' => 'required|numeric|min:1'
        ]);

        if ($validated['produit_type'] == 'produit_primaire') {
            $produit = ProduitPrimaire::findOrFail($validated['produit_id']);
        } else {
            $produit = ProduitSecondaire::findOrFail($validated['produit_id']);
        }

        $cart = session()->get('cart', []);
        Log::info('Current cart:', $cart);

        $item = [
            'produit_type' => $validated['produit_type'],
            'produit_id' => $validated['produit_id'],
            'nom' => $produit->nom,
            'prix' => $produit->prix_vente,
            'quantite' => $validated['quantite']
        ];

        $found = false;

        foreach ($cart as &$cartItem) {
            if ($cartItem['produit_type'] === $item['produit_type'] && $cartItem['produit_id'] === $item['produit_id']) {
                $cartItem['quantite'] += $item['quantite'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = $item;
        }

        session()->put('cart', $cart);
        Log::info('Updated cart:', $cart);

        return response()->json($cart);
    }



    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        DB::beginTransaction();

        try {
            $commande = Commande::create([
                'user_id' => Auth::id(),
                'date' => now(),
                'etat' => 'en attente'
            ]);

            if (isset($cart['produit_primaire'])) {
                foreach ($cart['produit_primaire'] as $produitId => $details) {
                    // Check availability
                    $produit = ProduitPrimaire::findOrFail($produitId);
                    // Optionally reduce stock quantity here
                    $commande->produitsPrimaires()->attach($produitId, ['quantite' => $details['quantite']]);
                }
            }

            if (isset($cart['produit_secondaire'])) {
                foreach ($cart['produit_secondaire'] as $produitId => $details) {
                    // Check availability
                    $produit = ProduitSecondaire::findOrFail($produitId);
                    // Optionally reduce stock quantity here
                    $commande->produitsSecondaires()->attach($produitId, ['quantite' => $details['quantite']]);
                }
            }
            DB::commit();

            session()->forget('cart');

            return response()->json([
                'message' => 'Checkout successful',
                'commande_id' => $commande->id
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Checkout failed, please try again'], 500);
        }
    }


    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|integer',
            'produit_type' => 'required|string|in:produit_primaire,produit_secondaire',
            'quantite' => 'required|numeric|min:0'
        ]);

        $cart = session()->get('cart', []);

        foreach ($cart as &$cartItem) {
            if ($cartItem['produit_type'] === $validated['produit_type'] && $cartItem['produit_id'] === $validated['produit_id']) {
                if ($validated['quantite'] > 0) {
                    $cartItem['quantite'] = $validated['quantite'];
                } else {
                    unset($cartItem);
                }
                session()->put('cart', array_values($cart)); // Re-index array
                return response()->json($cart);
            }
        }

        return response()->json($cart);
    }


    public function removeFromCart($produitId, $produitType)
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $key => $cartItem) {
            if ($cartItem['produit_type'] === $produitType && $cartItem['produit_id'] === $produitId) {
                unset($cart[$key]);
                session()->put('cart', array_values($cart)); // Re-index array
                return response()->json($cart);
            }
        }

        return response()->json($cart);
    }
}
