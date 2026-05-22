<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PurchaseIntentController;
use App\Http\Controllers\MaterialAllocationController;
use App\Http\Middleware\RequireRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [BomController::class, 'index'])
    ->middleware(['auth', 'verified', RequireRole::class . ':Admin|Engineer|Store Manager|Purchase Dept'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/bom-upload', [BomController::class, 'store'])
        ->middleware(RequireRole::class . ':Admin|Engineer|Store Manager')
        ->name('bom.upload');

    Route::get('/bom/{id}', [BomController::class, 'show'])
        ->middleware(RequireRole::class . ':Admin|Engineer|Store Manager')
        ->name('bom.show');

    Route::get('/purchase-intents', [PurchaseIntentController::class, 'index'])
        ->middleware(RequireRole::class . ':Admin|Purchase Dept')
        ->name('purchase.intents');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::get('/allocations', [MaterialAllocationController::class, 'index'])
        ->middleware(RequireRole::class . ':Admin|Store Manager')
        ->name('material.allocations');
});


require __DIR__.'/auth.php';
