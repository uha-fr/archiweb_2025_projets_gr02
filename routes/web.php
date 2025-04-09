<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AuthWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', [WebController::class, 'home'])->name('home');
Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);
Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthWebController::class, 'register']);

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AuthWebController::class, 'logout'])->name('logout');
    
    // Offres
    Route::get('/offers', [WebController::class, 'offers'])->name('offers.index');
    Route::get('/offers/create', [WebController::class, 'offerCreate'])->name('offers.create');
    Route::post('/offers', [WebController::class, 'offerStore'])->name('offers.store');
    Route::get('/offers/{id}', [WebController::class, 'offerShow'])->name('offers.show');
    
    // Historique
    Route::get('/history', [WebController::class, 'history'])->name('history');
});
