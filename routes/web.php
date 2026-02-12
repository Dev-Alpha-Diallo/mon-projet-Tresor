<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\MaisonController;
use App\Http\Controllers\BailleurController;
use App\Http\Controllers\PaiementBailleurController;
use App\Http\Controllers\RapportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Page d'accueil - Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des étudiants
    Route::resource('etudiants', EtudiantController::class);

    // Gestion des paiements
    Route::resource('paiements', PaiementController::class);

    // Gestion des factures
    Route::resource('factures', FactureController::class);
    
    // Route manquante pour générer le PDF d'une facture
    Route::get('/factures/{facture}/pdf', [FactureController::class, 'generatePdf'])
        ->name('factures.generate-pdf');

    // Gestion des maisons
    Route::resource('maisons', MaisonController::class);

    // Gestion des bailleurs
    Route::resource('bailleurs', BailleurController::class);

   // Route resource déjà gère index, create, store, etc.
Route::resource('paiements-bailleurs', PaiementBailleurController::class);

// PDF pour un paiement spécifique
Route::get('/paiements-bailleurs/{paiement}/pdf', [PaiementBailleurController::class, 'generatePdf'])
    ->name('paiements-bailleurs.generate-pdf');

// Prévisualisation pour un paiement spécifique
Route::get('/paiements-bailleurs/{paiement}/previsualiser', [PaiementBailleurController::class, 'previsualiser'])
    ->name('paiements-bailleurs.previsualiser'); // ✅ pas index
  


    // Gestion des rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::post('/generer', [RapportController::class, 'generer'])->name('generer');
        Route::get('/telecharger', [RapportController::class, 'telecharger'])->name('telecharger');
        Route::get('/previsualiser', [RapportController::class, 'previsualiser'])->name('previsualiser');
    });
});
