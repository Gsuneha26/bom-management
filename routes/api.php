<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BomApiController;
use App\Http\Controllers\Api\PurchaseIntentApiController;
use App\Http\Controllers\Api\AllocationApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/bom/upload', [BomApiController::class, 'upload']);

Route::get('/boms', [BomApiController::class, 'index']);

Route::get('/boms/{id}', [BomApiController::class, 'show']);

Route::get('/purchase-intents', [
    PurchaseIntentApiController::class,
    'index'
]);

Route::get('/allocations', [
    AllocationApiController::class,
    'index'
]);

Route::get('/boms/{id}/status', [
    BomApiController::class,
    'status'
]);