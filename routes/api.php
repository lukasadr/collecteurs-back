<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitPrimaireController;
use App\Http\Controllers\ProduitSecondaireController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Public
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::get('/user', function (Request $request) {return $request->user();});
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [CommandeController::class, 'dashboard']);

    // Cart
    Route::get('/panier', [PanierController::class, 'getCart']);
    Route::post('/panier', [PanierController::class, 'ajouter']);
    Route::put('/panier', [PanierController::class, 'updateCart']);

    // Notifications
    Route::get('/notification', [NotificationController::class, 'index']);
    Route::post('/mark-as-read', [NotificationController::class, 'readAll']);
    Route::delete('/notification-all', [NotificationController::class, 'deleteAll']);
    Route::delete('/notification/{id}', [NotificationController::class, 'destroy']);

    // Produit Primaire
    Route::get('/mouvement-primaire', [ProduitPrimaireController::class, 'indexMouvements']);
    Route::get('/mouvement-primaire/{id}', [ProduitPrimaireController::class, 'mouvementsProduit']);
    Route::post('/traiter/{id}', [ProduitPrimaireController::class, 'traiter']);
    Route::post('/produit-primaire/{id}', [ProduitPrimaireController::class, 'update']);

    //Produit Secondaire
    Route::get('/mouvement-secondaire', [ProduitSecondaireController::class, 'indexMouvements']);
    Route::get('/mouvement-secondaire/{id}', [ProduitSecondaireController::class, 'mouvementsProduit']);
    Route::post('/produit-secondaire/{id}', [ProduitSecondaireController::class, 'update']);

    //User
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::get('/users-all', [UserController::class, 'indexAll']);

    //Fournisseur
    Route::post('/fournisseur/{id}', [FournisseurController::class, 'update']);
    Route::post('/fourniture', [FournisseurController::class, 'fournir']);

    // Resources
    Route::resource('commande', CommandeController::class);
    Route::resource('evenement', EvenementController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('fournisseur', FournisseurController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('location', LocationController::class);
    Route::resource('machine', MachineController::class);
    Route::resource('mouvement', MouvementController::class);
    Route::resource('produit-primaire', ProduitPrimaireController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('produit-secondaire', ProduitSecondaireController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'destroy']);
});
