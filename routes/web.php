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
use App\Http\Controllers\Client\MensualiteController;
use App\Http\Controllers\Client\PaiementController as ClientPaiementController;
use App\Http\Controllers\Client\PaiementEnLigneController;
use App\Http\Controllers\Client\HistoriqueController;
use App\Http\Controllers\Client\ReclamationController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Client\ProfilController;
use App\Http\Controllers\Client\DocumentController;
use App\Http\Controllers\Client\AmicaleController;
use App\Http\Controllers\Client\LogementController;

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
    
    // Étudiants - ROUTES SPÉCIFIQUES AVANT RESOURCE
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
});

// ================= CLIENT ROUTES =================

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('mensualites')->name('mensualites.')->group(function () {
        Route::get('/', [MensualiteController::class, 'index'])->name('index');
        Route::get('/{id}', [MensualiteController::class, 'show'])->name('show');
    });
    Route::prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/', [ClientPaiementController::class, 'index'])->name('index');
        Route::get('/{id}/recu', [ClientPaiementController::class, 'downloadRecu'])->name('recu');
    });
    Route::prefix('paiement-en-ligne')->name('paiement-en-ligne.')->group(function () {
        Route::get('/', [PaiementEnLigneController::class, 'index'])->name('index');
        Route::post('/payer', [PaiementEnLigneController::class, 'payer'])->name('payer');
        Route::get('/confirmation/{reference}', [PaiementEnLigneController::class, 'confirmation'])->name('confirmation');
    });
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique');
    Route::get('/historique/pdf', [HistoriqueController::class, 'pdf'])->name('historique.pdf');
    Route::resource('reclamations', ReclamationController::class)->except(['edit', 'update']);
    Route::post('/reclamations/{id}/message', [ReclamationController::class, 'message'])->name('reclamations.message');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::put('/informations', [ProfilController::class, 'updateInfos'])->name('update-infos');
        Route::put('/password', [ProfilController::class, 'updatePassword'])->name('update-password');
        Route::post('/parent', [ProfilController::class, 'addParent'])->name('add-parent');
    });
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/contrat', [DocumentController::class, 'contrat'])->name('contrat');
        Route::get('/quittances', [DocumentController::class, 'quittances'])->name('quittances');
        Route::get('/reglement', [DocumentController::class, 'reglement'])->name('reglement');
        Route::get('/{id}/download', [DocumentController::class, 'download'])->name('download');
    });
    Route::prefix('amicale')->name('amicale.')->group(function () {
        Route::get('/', [AmicaleController::class, 'index'])->name('index');
        Route::get('/annonces', [AmicaleController::class, 'annonces'])->name('annonces');
        Route::get('/evenements', [AmicaleController::class, 'evenements'])->name('evenements');
        Route::get('/reunions', [AmicaleController::class, 'reunions'])->name('reunions');
        Route::get('/cotisations', [AmicaleController::class, 'cotisations'])->name('cotisations');
    });
    Route::prefix('logement')->name('logement.')->group(function () {
        Route::get('/', [LogementController::class, 'index'])->name('index');
        Route::get('/demande-changement', [LogementController::class, 'demandeChangement'])->name('demande-changement');
        Route::post('/demande-changement', [LogementController::class, 'soumettreChangement'])->name('soumettre-changement');
        Route::get('/demande-depart', [LogementController::class, 'demandeDepart'])->name('demande-depart');
        Route::post('/demande-depart', [LogementController::class, 'soumettreDepart'])->name('soumettre-depart');
        Route::get('/caution', [LogementController::class, 'caution'])->name('caution');
    });
});