<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Organisation:
| - Routes publiques (login, etc.)
| - Admin routes (routes/admin.php) → protégées par 'auth'
| - Client routes (routes/client.php) → pour interface client
*/

// ============================================================================
// ROUTES PUBLIQUES (Authentication)
// ============================================================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Déconnexion (accessible après auth)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================================================
// ADMIN ROUTES (Protégées par authentification)
// ============================================================================

Route::prefix('admin')
    ->middleware('auth')
    ->name('admin.')
    ->group(function () {
        require base_path('routes/admin.php');
    });

// Redirection : GET / vers dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth');

// ============================================================================
// CLIENT ROUTES (Publiques ou protégées selon besoin)
// ============================================================================

Route::prefix('client')
    ->name('client.')
    ->group(function () {
        require base_path('routes/client.php');
    });
