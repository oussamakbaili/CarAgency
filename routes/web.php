<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChooseRegisterController;
use App\Http\Controllers\Auth\ClientRegisterController;
use App\Http\Controllers\Auth\AgencyRegisterController;
use App\Http\Controllers\Admin\AgencyManagementController;
use App\Http\Controllers\Agency\{AgencyController, CarController, DashboardController, RentalController};
use App\Http\Controllers\Agency\AgencyDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Registration Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [ChooseRegisterController::class, 'choose'])->name('register.choose');
    
    Route::get('/register/client', [ClientRegisterController::class, 'create'])->name('register.client');
    Route::post('/register/client', [ClientRegisterController::class, 'store']);
    
    Route::get('register/agency', [App\Http\Controllers\Auth\AgencyRegisterController::class, 'showRegistrationForm'])
        ->name('register.agency');
    Route::post('register/agency', [App\Http\Controllers\Auth\AgencyRegisterController::class, 'register']);
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Agency Management
    Route::get('/agencies', [App\Http\Controllers\Admin\AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/{agency}', [App\Http\Controllers\Admin\AgencyController::class, 'show'])->name('agencies.show');
    Route::post('/agencies/{agency}/approve', [App\Http\Controllers\Admin\AgencyController::class, 'approve'])->name('agencies.approve');
    Route::post('/agencies/{agency}/reject', [App\Http\Controllers\Admin\AgencyController::class, 'reject'])->name('agencies.reject');
    
    // Rentals Management
    Route::get('/rentals', [App\Http\Controllers\Admin\RentalController::class, 'index'])->name('rentals.index');
});

// Agency Routes
Route::middleware(['auth', 'role:agence'])->prefix('agence')->name('agence.')->group(function () {
    // Routes accessible to all agencies regardless of status
    Route::get('/pending', [AgencyController::class, 'showPending'])->name('pending');

    Route::get('/rejected', [AgencyController::class, 'showRejected'])->name('rejected');
    Route::put('/update', [AgencyController::class, 'update'])->name('update');

    // Routes only accessible to approved agencies
    Route::middleware('approved.agency')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('cars', CarController::class);
        
        // Rental routes
        Route::get('/rentals/pending', [RentalController::class, 'pending'])->name('rentals.pending');
        Route::patch('/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('rentals.approve');
        Route::delete('/rentals/{rental}/reject', [RentalController::class, 'reject'])->name('rentals.reject');
    });
});

// Client Routes
Route::prefix('client')->middleware(['auth', 'verified', 'client'])->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:agency'])->group(function () {
    Route::get('/agence/dashboard', [AgencyDashboardController::class, 'index'])->name('agence.dashboard');
});

require __DIR__ . '/auth.php';
