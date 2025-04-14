<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\TransactionController;



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
    Route::get('/offers/{id}/edit', [WebController::class, 'offerEdit'])->name('offers.edit');
    Route::put('/offers/{id}', [WebController::class, 'offerUpdate'])->name('offers.update');
    Route::post('/offers/{id}/toggle-status', [WebController::class, 'offerToggleStatus'])->name('offers.toggle-status');
    Route::delete('/offers/{id}', [WebController::class, 'offerDestroy'])->name('offers.destroy');
    
    // Contrats
    Route::post('/contracts', [WebController::class, 'contractStore'])->name('contracts.store');
    Route::get('/contracts/pending', [WebController::class, 'pendingContracts'])->name('contracts.pending');
    Route::post('/contracts/{id}/accept', [WebController::class, 'contractAccept'])->name('contracts.accept');
    Route::post('/contracts/{id}/reject', [WebController::class, 'contractReject'])->name('contracts.reject');
    Route::get('/contracts/{id}', [WebController::class, 'contractShow'])->name('contracts.show');
    
    // Historique
    Route::get('/history', [WebController::class, 'history'])->name('history');

    // Centre de messagerie
    Route::get('/chat', [ChatController::class, 'chatcenter'])->name('chatcenter');
    Route::get('/chat/user/{id}', [ChatController::class, 'chatcenter'])->name('chat.with');
    Route::post('/chat/{id}', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/message/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');

    // Compteur et Solde
    Route::get('/compteur', [WebController::class, 'compteur'])->name('compteur');  
    Route::get('/solde', [WebController::class, 'solde'])->name('solde');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // Profil
    Route::get('/profile', [WebController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [WebController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/profile', [WebController::class, 'profileUpdate'])->name('profile.update');
    Route::delete('/profile/photo', [WebController::class, 'profilePhotoDelete'])->name('profile.photo.delete');

    // Public profil
    Route::get('/publicprofile/{user}', [WebController::class, 'publicProfile'])->name('publicProfile');

    // Notifications
    Route::post('/notifications/{notification}/mark-read', function ($notificationId) {
      $notification = auth()->user()->notifications()->findOrFail($notificationId);
      $notification->markAsRead(); 
      return back(); 
  })->name('notifications.mark-read');
  

});