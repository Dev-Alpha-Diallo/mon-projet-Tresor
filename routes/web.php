<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EtudiantController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\FactureController;
use App\Http\Controllers\Admin\MaisonController;
use App\Http\Controllers\Admin\BailleurController;
use App\Http\Controllers\Admin\PaiementBailleurController;
use App\Http\Controllers\Admin\RapportController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;

// ================= PUBLIC ROUTES =================

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ================= ADMIN ROUTES =================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Étudiants
    Route::get('etudiants/search', [EtudiantController::class, 'search'])->name('etudiants.search');
    Route::get('etudiants/export/tous', [EtudiantController::class, 'exportTous'])->name('etudiants.export.tous');
    Route::get('etudiants/export/debiteurs', [EtudiantController::class, 'exportDebiteurs'])->name('etudiants.export.debiteurs');
    Route::resource('etudiants', EtudiantController::class);
    
    Route::resource('paiements', PaiementController::class);
    
    Route::resource('factures', FactureController::class);
    Route::get('factures/{facture}/pdf', [FactureController::class, 'generatePdf'])->name('factures.generate-pdf');
    
    Route::resource('maisons', MaisonController::class);
    Route::resource('bailleurs', BailleurController::class);
    
    Route::resource('paiements-bailleurs', PaiementBailleurController::class);
    Route::get('paiements-bailleurs/{paiement}/pdf', [PaiementBailleurController::class, 'generatePdf'])->name('paiements-bailleurs.generate-pdf');
    Route::get('paiements-bailleurs/{paiement}/previsualiser', [PaiementBailleurController::class, 'previsualiser'])->name('paiements-bailleurs.previsualiser');
    
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::post('/generer', [RapportController::class, 'generer'])->name('generer');
        Route::get('/telecharger', [RapportController::class, 'telecharger'])->name('telecharger');
        Route::get('/previsualiser', [RapportController::class, 'previsualiser'])->name('previsualiser');
    });

    // ← Notifications Wave
    Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/valider', [App\Http\Controllers\Admin\NotificationController::class, 'valider'])->name('notifications.valider');
    Route::post('notifications/{id}/rejeter', [App\Http\Controllers\Admin\NotificationController::class, 'rejeter'])->name('notifications.rejeter');

});
     // ================= CLIENT ROUTES =================

// Routes publiques client (guest seulement)
Route::middleware('guest')->prefix('client')->name('client.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Client\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Client\AuthController::class, 'login'])->name('login.post');
});

// Routes protégées client
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [App\Http\Controllers\Client\ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/password', [App\Http\Controllers\Client\ProfilController::class, 'updatePassword'])->name('profil.password');


     // Détail paiement
    Route::get('/paiements/{paiement}', [App\Http\Controllers\Client\PaiementController::class, 'show'])->name('paiements.show');

    // ... routes existantes ...
    Route::get('/payer', [App\Http\Controllers\Client\PaiementWaveController::class, 'index'])->name('paiement.wave');
    Route::post('/payer', [App\Http\Controllers\Client\PaiementWaveController::class, 'soumettre'])->name('paiement.soumettre');

  

    });


