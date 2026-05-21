<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [BomController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/bom-upload', [BomController::class, 'store'])->name('bom.upload');
    Route::get('/bom/{id}', [BomController::class, 'show'])
        ->name('bom.show');
    Route::get('/purchase-intents', [PurchaseIntentController::class, 'index'])
    ->name('purchase.intents');
});


require __DIR__.'/auth.php';
