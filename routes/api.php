<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API pour les sites externes - vérification de disponibilité
Route::post('/cars/check-availability', [App\Http\Controllers\Api\ExternalSiteAvailabilityController::class, 'checkAvailability'])
    ->middleware('api'); // Vous pouvez ajouter un middleware d'authentification spécifique si nécessaire

// API pour la découverte de sites externes
Route::post('/external-sites/register', [App\Http\Controllers\Api\ExternalSiteDiscoveryController::class, 'register'])
    ->middleware('api');
    
Route::get('/cars/{registration_number}/external-sites', [App\Http\Controllers\Api\ExternalSiteDiscoveryController::class, 'listExternalSites'])
    ->middleware('api');
