<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FournisseurController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produit', [FournisseurController::class, 'test']);
