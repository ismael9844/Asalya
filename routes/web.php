<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [PropertyController::class, 'landing'])->name('landing');

// Changer la langue
Route::get('/set-language', [PropertyController::class, 'setLanguage'])->name('set.language');

// Tableau de bord : accessible uniquement aux utilisateurs connectés et vérifiés
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/properties/landing', [PropertyController::class, 'landing'])->name('properties.landing');

// 🏠 Routes PUBLIQUES des propriétés (lecture seule)
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/api/properties', [PropertyController::class, 'apiProperties']);

// 🔐 Routes nécessitant l'authentification
Route::middleware('auth')->group(function () {

    // Gestion du profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔐🔑 Routes properties réservées aux administrateurs (création, modification, suppression)
// IMPORTANT : ces routes statiques doivent être déclarées AVANT la route wildcard
// GET /properties/{property} plus bas, sinon Laravel matche 'create' comme un {property}.
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
});

// Route wildcard — doit rester la DERNIÈRE route GET /properties/... déclarée
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

require __DIR__.'/auth.php';