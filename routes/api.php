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

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\UserController;

// ─── Routes publiques (sans authentification) ───────────────────────────────
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Social & Phone auth
Route::post('/auth/phone/send',   [AuthController::class, 'sendPhoneOtp']);
Route::post('/auth/phone/verify', [AuthController::class, 'verifyPhoneOtp']);
Route::post('/auth/google',       [AuthController::class, 'googleAuth']);
Route::post('/auth/apple',        [AuthController::class, 'appleAuth']);

Route::get('/cars',          [CarController::class, 'index']);
Route::get('/cars/featured', [CarController::class, 'featured']);
Route::get('/cars/{id}',     [CarController::class, 'show']);

// ─── Routes protégées (token Sanctum requis) ────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Profil
    Route::get('/profile',  [UserController::class, 'show']);
    Route::put('/profile',  [UserController::class, 'update']);

    // Réservations
    Route::get('/bookings',         [BookingController::class, 'index']);
    Route::post('/bookings',        [BookingController::class, 'store']);
    Route::get('/bookings/{id}',    [BookingController::class, 'show']);
    Route::delete('/bookings/{id}', [BookingController::class, 'cancel']);

    // Favoris
    Route::post('/cars/{id}/favorite', [CarController::class, 'toggleFavorite']);
    Route::get('/favorites',           [CarController::class, 'favorites']);
});

// API pour les sites externes - vérification de disponibilité
Route::post('/cars/check-availability', [App\Http\Controllers\Api\ExternalSiteAvailabilityController::class, 'checkAvailability'])
    ->middleware('api'); // Vous pouvez ajouter un middleware d'authentification spécifique si nécessaire

// API pour la découverte de sites externes
Route::post('/external-sites/register', [App\Http\Controllers\Api\ExternalSiteDiscoveryController::class, 'register'])
    ->middleware('api');
    
Route::get('/cars/{registration_number}/external-sites', [App\Http\Controllers\Api\ExternalSiteDiscoveryController::class, 'listExternalSites'])
    ->middleware('api');
