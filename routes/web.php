<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChooseRegisterController;
use App\Http\Controllers\Auth\ClientRegisterController;
use App\Http\Controllers\Auth\AgencyRegisterController;
use App\Http\Controllers\Admin\AgencyManagementController;
use App\Http\Controllers\Agency\{AgencyController, CarController, DashboardController, RentalController};
use App\Http\Controllers\Agency\AgencyDashboardController;
use App\Http\Controllers\Agency\AgencyController as PublicAgencyController;

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
})->name('welcome');

// Agency Registration Routes
Route::prefix('agency')->name('agency.')->group(function () {
    Route::get('/register', [PublicAgencyController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [PublicAgencyController::class, 'register'])->name('register');
    Route::get('/success', [PublicAgencyController::class, 'success'])->name('register.success');
});

// General dashboard route that redirects based on user role
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    $user = auth()->user();
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'agence':
            return redirect()->route('agence.dashboard');
        case 'client':
            return redirect()->route('client.dashboard');
        default:
            return redirect('/');
    }
})->middleware('auth')->name('dashboard');

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
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/activity', [App\Http\Controllers\Admin\DashboardController::class, 'getActivity'])->name('dashboard.activity');
    Route::get('/dashboard/charts', [App\Http\Controllers\Admin\DashboardController::class, 'getCharts'])->name('dashboard.charts');
    
    // Agency Management
    Route::get('/agencies', [App\Http\Controllers\Admin\AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/pending', [App\Http\Controllers\Admin\AgencyController::class, 'pending'])->name('agencies.pending');
    Route::get('/agencies/documents', [App\Http\Controllers\Admin\AgencyController::class, 'documents'])->name('agencies.documents');
    Route::get('/agencies/{agency}', [App\Http\Controllers\Admin\AgencyController::class, 'show'])->name('agencies.show');
    Route::get('/agencies/{agency}/performance', [App\Http\Controllers\Admin\AgencyController::class, 'performance'])->name('agencies.performance');
    Route::post('/agencies/{agency}/approve', [App\Http\Controllers\Admin\AgencyController::class, 'approve'])->name('agencies.approve');
    Route::post('/agencies/{agency}/reject', [App\Http\Controllers\Admin\AgencyController::class, 'reject'])->name('agencies.reject');
    Route::post('/agencies/bulk-approve', [App\Http\Controllers\Admin\AgencyController::class, 'bulkApprove'])->name('agencies.bulk-approve');
    Route::post('/agencies/bulk-reject', [App\Http\Controllers\Admin\AgencyController::class, 'bulkReject'])->name('agencies.bulk-reject');
    Route::put('/agencies/{agency}/commission', [App\Http\Controllers\Admin\AgencyController::class, 'updateCommission'])->name('agencies.update-commission');
    
    // Agency Suspension Management
    Route::get('/agencies/suspended', [App\Http\Controllers\Admin\AgencySuspensionController::class, 'index'])->name('agencies.suspended');
    Route::get('/agencies/suspension/{agency}', [App\Http\Controllers\Admin\AgencySuspensionController::class, 'show'])->name('agencies.suspension.show');
    Route::post('/agencies/suspension/{agency}/suspend', [App\Http\Controllers\Admin\AgencySuspensionController::class, 'suspend'])->name('agencies.suspension.suspend');
    Route::patch('/agencies/suspension/{agency}/unsuspend', [App\Http\Controllers\Admin\AgencySuspensionController::class, 'unsuspend'])->name('agencies.suspension.unsuspend');
    Route::patch('/agencies/suspension/{agency}/reset-cancellations', [App\Http\Controllers\Admin\AgencySuspensionController::class, 'resetCancellations'])->name('agencies.suspension.reset-cancellations');
    Route::patch('/agencies/suspension/{agency}/update-max-cancellations', [App\Http\Controllers\Admin\AgencySuspensionController::class, 'updateMaxCancellations'])->name('agencies.suspension.update-max-cancellations');
    
    // Customer Management
    Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/profiles', [App\Http\Controllers\Admin\CustomerController::class, 'profiles'])->name('customers.profiles');
    Route::get('/customers/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/booking-history', [App\Http\Controllers\Admin\CustomerController::class, 'bookingHistory'])->name('customers.booking-history');
    Route::get('/customers/support-tickets', [App\Http\Controllers\Admin\CustomerController::class, 'supportTickets'])->name('customers.support-tickets');
    Route::get('/customers/export', [App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('customers.export');
    
    // Vehicle Management
    Route::get('/vehicles', [App\Http\Controllers\Admin\VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/categories', [App\Http\Controllers\Admin\VehicleController::class, 'categories'])->name('vehicles.categories');
    Route::get('/vehicles/fleet-analytics', [App\Http\Controllers\Admin\VehicleController::class, 'fleetAnalytics'])->name('vehicles.fleet-analytics');
    Route::get('/vehicles/{vehicle}', [App\Http\Controllers\Admin\VehicleController::class, 'show'])->name('vehicles.show');
    Route::put('/vehicles/{vehicle}/status', [App\Http\Controllers\Admin\VehicleController::class, 'updateStatus'])->name('vehicles.update-status');
    Route::get('/vehicles/export', [App\Http\Controllers\Admin\VehicleController::class, 'export'])->name('vehicles.export');
    
    // Booking Management
    Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/active', [App\Http\Controllers\Admin\BookingController::class, 'active'])->name('bookings.active');
    Route::get('/bookings/calendar', [App\Http\Controllers\Admin\BookingController::class, 'calendar'])->name('bookings.calendar');
    Route::get('/bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::get('/bookings/analytics', [App\Http\Controllers\Admin\BookingController::class, 'analytics'])->name('bookings.analytics');
    
    // Financial Management
    Route::get('/finance/dashboard', [App\Http\Controllers\Admin\FinanceController::class, 'dashboard'])->name('finance.dashboard');
    Route::get('/finance/commissions', [App\Http\Controllers\Admin\FinanceController::class, 'commissions'])->name('finance.commissions');
    Route::get('/finance/payments', [App\Http\Controllers\Admin\FinanceController::class, 'payments'])->name('finance.payments');
    Route::get('/finance/reports', [App\Http\Controllers\Admin\FinanceController::class, 'reports'])->name('finance.reports');
    Route::get('/finance/payouts', [App\Http\Controllers\Admin\FinanceController::class, 'payouts'])->name('finance.payouts');
    Route::post('/finance/payouts/{agency}/process', [App\Http\Controllers\Admin\FinanceController::class, 'processPayout'])->name('finance.process-payout');
    
    // Content Management
    Route::get('/content/settings', [App\Http\Controllers\Admin\ContentController::class, 'settings'])->name('content.settings');
    Route::get('/content/banners', [App\Http\Controllers\Admin\ContentController::class, 'banners'])->name('content.banners');
    Route::get('/content/faq', [App\Http\Controllers\Admin\ContentController::class, 'faq'])->name('content.faq');
    Route::get('/content/policies', [App\Http\Controllers\Admin\ContentController::class, 'policies'])->name('content.policies');
    
    // User Management
    Route::get('/users/admins', [App\Http\Controllers\Admin\UserController::class, 'admins'])->name('users.admins');
    Route::get('/users/roles', [App\Http\Controllers\Admin\UserController::class, 'roles'])->name('users.roles');
    Route::get('/users/activity-logs', [App\Http\Controllers\Admin\UserController::class, 'activityLogs'])->name('users.activity-logs');
    Route::get('/users/security', [App\Http\Controllers\Admin\UserController::class, 'security'])->name('users.security');
    
    // Reports & Analytics
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/custom', [App\Http\Controllers\Admin\ReportController::class, 'custom'])->name('reports.custom');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/performance', [App\Http\Controllers\Admin\ReportController::class, 'performance'])->name('reports.performance');
    Route::get('/reports/audit', [App\Http\Controllers\Admin\ReportController::class, 'audit'])->name('reports.audit');
    
    // System Management
    Route::get('/system/health', [App\Http\Controllers\Admin\SystemController::class, 'health'])->name('system.health');
    Route::get('/system/backups', [App\Http\Controllers\Admin\SystemController::class, 'backups'])->name('system.backups');
    Route::get('/system/emails', [App\Http\Controllers\Admin\SystemController::class, 'emails'])->name('system.emails');
    Route::get('/system/api', [App\Http\Controllers\Admin\SystemController::class, 'api'])->name('system.api');
    Route::get('/system/maintenance', [App\Http\Controllers\Admin\SystemController::class, 'maintenance'])->name('system.maintenance');
    Route::post('/system/maintenance/toggle', [App\Http\Controllers\Admin\SystemController::class, 'toggleMaintenance'])->name('system.toggle-maintenance');
    Route::post('/system/clear-cache', [App\Http\Controllers\Admin\SystemController::class, 'clearCache'])->name('system.clear-cache');
    Route::post('/system/optimize', [App\Http\Controllers\Admin\SystemController::class, 'optimize'])->name('system.optimize');
    
    // Legacy routes (keeping for backward compatibility)
    Route::get('/rentals', [App\Http\Controllers\Admin\RentalController::class, 'index'])->name('rentals.index');
});

// Agency Routes
Route::middleware(['auth', 'role:agence'])->prefix('agence')->name('agence.')->group(function () {
    // Routes accessible to all agencies regardless of status
    Route::get('/pending', [AgencyController::class, 'showPending'])->name('pending');
    Route::get('/rejected', [AgencyController::class, 'showRejected'])->name('rejected');
    Route::get('/suspended', [AgencyController::class, 'showSuspended'])->name('suspended');
    Route::put('/update', [AgencyController::class, 'update'])->name('update');

    // Routes only accessible to approved agencies
    Route::middleware('approved.agency')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Fleet Management
        Route::resource('cars', CarController::class);
        Route::get('/fleet', [CarController::class, 'index'])->name('fleet.index');
        Route::get('/fleet/categories', [App\Http\Controllers\Agency\CategoryController::class, 'index'])->name('fleet.categories');
        Route::get('/fleet/maintenance', [App\Http\Controllers\Agency\MaintenanceController::class, 'index'])->name('fleet.maintenance');
        Route::get('/fleet/analytics', [CarController::class, 'analytics'])->name('fleet.analytics');
        
        // Category Management
        Route::resource('categories', App\Http\Controllers\Agency\CategoryController::class);
        
        // Maintenance Management
        Route::resource('maintenances', App\Http\Controllers\Agency\MaintenanceController::class);
        Route::patch('/maintenances/{maintenance}/status', [App\Http\Controllers\Agency\MaintenanceController::class, 'updateStatus'])->name('maintenances.update-status');
        
        // Booking Management
        Route::get('/bookings', [RentalController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/pending', [RentalController::class, 'pending'])->name('bookings.pending');
        Route::get('/bookings/active', [RentalController::class, 'active'])->name('bookings.active');
        Route::get('/bookings/calendar', [RentalController::class, 'calendar'])->name('bookings.calendar');
        Route::get('/bookings/history', [RentalController::class, 'history'])->name('bookings.history');
        
        // Cancellation Management
        Route::get('/rentals/{rental}/cancel', [App\Http\Controllers\Agency\CancellationController::class, 'show'])->name('rentals.cancel');
        Route::patch('/rentals/{rental}/cancel', [App\Http\Controllers\Agency\CancellationController::class, 'cancel'])->name('rentals.cancel');
        Route::get('/cancellation/stats', [App\Http\Controllers\Agency\CancellationController::class, 'stats'])->name('cancellation.stats');
        
        // Rental Management (for backward compatibility)
        Route::get('/rentals/pending', [RentalController::class, 'pending'])->name('rentals.pending');
        Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
        Route::patch('/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('rentals.approve');
        Route::patch('/rentals/{rental}/reject', [RentalController::class, 'reject'])->name('rentals.reject');
        Route::get('/rentals/{rental}/invoice', [RentalController::class, 'invoice'])->name('rentals.invoice');
        Route::get('/rentals/{rental}/invoice/download', [RentalController::class, 'downloadInvoice'])->name('rentals.invoice.download');
        
        // Customer Management
        Route::get('/customers', [App\Http\Controllers\Agency\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/profiles', [App\Http\Controllers\Agency\CustomerController::class, 'profiles'])->name('customers.profiles');
        Route::get('/customers/reviews', [App\Http\Controllers\Agency\CustomerController::class, 'reviews'])->name('customers.reviews');
        Route::get('/customers/reviews/{id}/data', [App\Http\Controllers\Agency\CustomerController::class, 'getReviewData'])->name('customers.reviews.data');
        Route::post('/customers/reviews/{id}/reply', [App\Http\Controllers\Agency\CustomerController::class, 'replyToReview'])->name('customers.reviews.reply');
        Route::get('/customers/support', [App\Http\Controllers\Agency\CustomerController::class, 'support'])->name('customers.support');
        Route::get('/customers/support/{id}/details', [App\Http\Controllers\Agency\CustomerController::class, 'getTicketDetails'])->name('customers.support.details');
        Route::post('/customers/support/{id}/reply', [App\Http\Controllers\Agency\CustomerController::class, 'replyToTicket'])->name('customers.support.reply');
        Route::post('/customers/support/{id}/update-status', [App\Http\Controllers\Agency\CustomerController::class, 'updateTicketStatus'])->name('customers.support.update-status');
        Route::get('/customers/{customer}', [App\Http\Controllers\Agency\CustomerController::class, 'show'])->name('customers.show');
        
        // Financial Management
        Route::get('/finance', [App\Http\Controllers\Agency\FinanceController::class, 'index'])->name('finance.index');
        Route::get('/finance/payments', [App\Http\Controllers\Agency\FinanceController::class, 'payments'])->name('finance.payments');
        Route::get('/finance/commissions', [App\Http\Controllers\Agency\FinanceController::class, 'commissions'])->name('finance.commissions');
        Route::get('/finance/export', [App\Http\Controllers\Agency\FinanceController::class, 'export'])->name('finance.export');
        Route::post('/finance/request-payment', [App\Http\Controllers\Agency\FinanceController::class, 'requestPayment'])->name('finance.request-payment');
        Route::get('/finance/reports', [App\Http\Controllers\Agency\FinanceController::class, 'reports'])->name('finance.reports');
        Route::get('/finance/payouts', [App\Http\Controllers\Agency\FinanceController::class, 'payouts'])->name('finance.payouts');
        Route::get('/finance/payments/{id}/details', [App\Http\Controllers\Agency\FinanceController::class, 'getPaymentDetails'])->name('finance.payments.details');
        Route::post('/finance/payments/{id}/approve', [App\Http\Controllers\Agency\FinanceController::class, 'approvePayment'])->name('finance.payments.approve');
        Route::post('/finance/payments/{id}/refund', [App\Http\Controllers\Agency\FinanceController::class, 'refundPayment'])->name('finance.payments.refund');
        Route::get('/finance/export-payments', [App\Http\Controllers\Agency\FinanceController::class, 'exportPayments'])->name('finance.export-payments');
        Route::get('/finance/commissions/{id}/details', [App\Http\Controllers\Agency\FinanceController::class, 'getCommissionDetails'])->name('finance.commissions.details');
        Route::get('/finance/commissions/{id}/report', [App\Http\Controllers\Agency\FinanceController::class, 'downloadCommissionReport'])->name('finance.commissions.report');
        Route::get('/finance/export-commissions', [App\Http\Controllers\Agency\FinanceController::class, 'exportCommissions'])->name('finance.export-commissions');
        Route::get('/finance/export-payouts', [App\Http\Controllers\Agency\FinanceController::class, 'exportPayouts'])->name('finance.export-payouts');
        Route::get('/finance/payouts/{id}/details', [App\Http\Controllers\Agency\FinanceController::class, 'getPayoutDetails'])->name('finance.payouts.details');
        
        // Admin routes for payment requests
        Route::get('/admin/payment-requests', [App\Http\Controllers\Admin\PaymentRequestController::class, 'index'])->name('admin.payment-requests');
        Route::post('/admin/payment-requests/{id}/approve', [App\Http\Controllers\Admin\PaymentRequestController::class, 'approve'])->name('admin.payment-requests.approve');
        Route::post('/admin/payment-requests/{id}/reject', [App\Http\Controllers\Admin\PaymentRequestController::class, 'reject'])->name('admin.payment-requests.reject');
        
        // Pricing & Availability
        Route::get('/pricing', [App\Http\Controllers\Agency\PricingController::class, 'index'])->name('pricing.index');
        Route::get('/pricing/dynamic', [App\Http\Controllers\Agency\PricingController::class, 'dynamic'])->name('pricing.dynamic');
        Route::get('/pricing/seasonal', [App\Http\Controllers\Agency\PricingController::class, 'seasonal'])->name('pricing.seasonal');
        Route::get('/pricing/offers', [App\Http\Controllers\Agency\PricingController::class, 'offers'])->name('pricing.offers');
        Route::post('/pricing/update', [App\Http\Controllers\Agency\PricingController::class, 'updatePricing'])->name('pricing.update');
        Route::get('/pricing/competitor-analysis', [App\Http\Controllers\Agency\PricingController::class, 'competitorAnalysis'])->name('pricing.competitor-analysis');
        Route::get('/pricing/car/{id}/history', [App\Http\Controllers\Agency\PricingController::class, 'carPricingHistory'])->name('pricing.car.history');
        Route::get('/pricing/car/{id}/edit', [App\Http\Controllers\Agency\PricingController::class, 'editCarPricing'])->name('pricing.car.edit');
        Route::post('/pricing/dynamic/configure', [App\Http\Controllers\Agency\PricingController::class, 'configureDynamicPricing'])->name('pricing.dynamic.configure');
        Route::post('/pricing/seasonal/create', [App\Http\Controllers\Agency\PricingController::class, 'createSeasonalRule'])->name('pricing.seasonal.create');
        Route::get('/pricing/seasonal/{id}/edit', [App\Http\Controllers\Agency\PricingController::class, 'editSeasonalRule'])->name('pricing.seasonal.edit');
        Route::put('/pricing/seasonal/{id}', [App\Http\Controllers\Agency\PricingController::class, 'updateSeasonalRule'])->name('pricing.seasonal.update');
        Route::patch('/pricing/seasonal/{id}/toggle', [App\Http\Controllers\Agency\PricingController::class, 'toggleSeasonalRule'])->name('pricing.seasonal.toggle');
        Route::delete('/pricing/seasonal/{id}', [App\Http\Controllers\Agency\PricingController::class, 'deleteSeasonalRule'])->name('pricing.seasonal.delete');
        Route::get('/pricing/offers', [App\Http\Controllers\Agency\PricingController::class, 'offers'])->name('pricing.offers');
        Route::post('/pricing/offers/create', [App\Http\Controllers\Agency\PricingController::class, 'createOffer'])->name('pricing.offers.create');
        Route::get('/pricing/offers/{id}/edit', [App\Http\Controllers\Agency\PricingController::class, 'editOffer'])->name('pricing.offers.edit');
        Route::put('/pricing/offers/{id}', [App\Http\Controllers\Agency\PricingController::class, 'updateOffer'])->name('pricing.offers.update');
        Route::delete('/pricing/offers/{id}', [App\Http\Controllers\Agency\PricingController::class, 'deleteOffer'])->name('pricing.offers.delete');
        
        // Reports & Analytics
        Route::get('/reports', [App\Http\Controllers\Agency\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/performance', [App\Http\Controllers\Agency\ReportController::class, 'performance'])->name('reports.performance');
        Route::get('/reports/revenue', [App\Http\Controllers\Agency\ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/performance/export/csv', [App\Http\Controllers\Agency\ReportController::class, 'exportPerformanceCSV'])->name('reports.performance.export.csv');
        Route::get('/reports/performance/export/pdf', [App\Http\Controllers\Agency\ReportController::class, 'exportPerformancePDF'])->name('reports.performance.export.pdf');
        Route::get('/reports/customers', [App\Http\Controllers\Agency\ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/reports/fleet', [App\Http\Controllers\Agency\ReportController::class, 'fleet'])->name('reports.fleet');
        
        // Profile & Settings
        Route::get('/profile', [App\Http\Controllers\Agency\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/general', [App\Http\Controllers\Agency\ProfileController::class, 'updateGeneral'])->name('profile.general');
        Route::put('/profile/hours', [App\Http\Controllers\Agency\ProfileController::class, 'updateOpeningHours'])->name('profile.hours');
        Route::put('/profile/locations', [App\Http\Controllers\Agency\ProfileController::class, 'updateLocations'])->name('profile.locations');
        Route::post('/profile/documents/upload', [App\Http\Controllers\Agency\ProfileController::class, 'uploadDocument'])->name('profile.documents.upload');
        Route::delete('/profile/documents/{index}', [App\Http\Controllers\Agency\ProfileController::class, 'deleteDocument'])->name('profile.documents.delete');
        Route::put('/profile/security', [App\Http\Controllers\Agency\ProfileController::class, 'updateSecurity'])->name('profile.security');
        Route::post('/profile/picture', [App\Http\Controllers\Agency\ProfileController::class, 'updateProfilePicture'])->name('profile.picture');
        
        // Marketing & Promotions
        Route::get('/marketing', [App\Http\Controllers\Agency\MarketingController::class, 'index'])->name('marketing.index');
        Route::get('/marketing/campaigns', [App\Http\Controllers\Agency\MarketingController::class, 'campaigns'])->name('marketing.campaigns');
        Route::get('/marketing/communications', [App\Http\Controllers\Agency\MarketingController::class, 'communications'])->name('marketing.communications');
        Route::get('/marketing/referrals', [App\Http\Controllers\Agency\MarketingController::class, 'referrals'])->name('marketing.referrals');
        
        // Support & Help
        Route::get('/support', [App\Http\Controllers\Agency\SupportController::class, 'index'])->name('support.index');
        Route::post('/support', [App\Http\Controllers\Agency\SupportController::class, 'store'])->name('support.store');
        Route::get('/support/tickets/{id}', [App\Http\Controllers\Agency\SupportController::class, 'show'])->name('support.show');
        Route::post('/support/tickets/{id}/reply', [App\Http\Controllers\Agency\SupportController::class, 'reply'])->name('support.reply');
        Route::get('/support/contact', [App\Http\Controllers\Agency\SupportController::class, 'contact'])->name('support.contact');
        Route::get('/support/training', [App\Http\Controllers\Agency\SupportController::class, 'training'])->name('support.training');
    });
});

// Client Routes
Route::prefix('client')->middleware(['auth', 'verified', 'client'])->name('client.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
    
    // Car browsing
    Route::get('/cars', [App\Http\Controllers\Client\CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [App\Http\Controllers\Client\CarController::class, 'show'])->name('cars.show');
    
    // Car reviews
    Route::post('/cars/{car}/reviews', [App\Http\Controllers\Client\CarReviewController::class, 'store'])->name('cars.reviews.store');
    Route::put('/reviews/{avis}', [App\Http\Controllers\Client\CarReviewController::class, 'update'])->name('cars.reviews.update');
    Route::delete('/reviews/{avis}', [App\Http\Controllers\Client\CarReviewController::class, 'destroy'])->name('cars.reviews.destroy');
    
    // Rental management
    Route::get('/rentals', [App\Http\Controllers\Client\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [App\Http\Controllers\Client\RentalController::class, 'show'])->name('rentals.show');
    Route::get('/cars/{car}/rent', [App\Http\Controllers\Client\RentalController::class, 'create'])->name('rentals.create');
    Route::post('/cars/{car}/rent', [App\Http\Controllers\Client\RentalController::class, 'store'])->name('rentals.store');
    Route::patch('/rentals/{rental}/cancel', [App\Http\Controllers\Client\RentalController::class, 'cancel'])->name('rentals.cancel');
    Route::get('/cars/{car}/unavailable-dates', [App\Http\Controllers\Client\RentalController::class, 'getUnavailableDates'])->name('rentals.unavailable-dates');
    
        // Profile management
        Route::get('/profile', [App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/general', [App\Http\Controllers\Client\ProfileController::class, 'updateGeneral'])->name('profile.general');
        Route::put('/profile/security', [App\Http\Controllers\Client\ProfileController::class, 'updateSecurity'])->name('profile.security');
        Route::put('/profile/preferences', [App\Http\Controllers\Client\ProfileController::class, 'updatePreferences'])->name('profile.preferences');
        Route::post('/profile/picture', [App\Http\Controllers\Client\ProfileController::class, 'updateProfilePicture'])->name('profile.picture');
        Route::delete('/profile/picture', [App\Http\Controllers\Client\ProfileController::class, 'deleteProfilePicture'])->name('profile.picture.delete');
        Route::post('/profile/documents', [App\Http\Controllers\Client\ProfileController::class, 'updateDocuments'])->name('profile.documents');
        Route::delete('/profile/documents/{type}', [App\Http\Controllers\Client\ProfileController::class, 'deleteDocument'])->name('profile.documents.delete');
        
        // Agencies
        Route::get('/agencies', [App\Http\Controllers\Client\AgencyController::class, 'index'])->name('agencies.index');
        Route::get('/agencies/{agency}', [App\Http\Controllers\Client\AgencyController::class, 'show'])->name('agencies.show');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Temporary debug route to check database data
Route::get('/debug/data', function () {
    return response()->json([
        'users_total' => \App\Models\User::count(),
        'agencies_total' => \App\Models\Agency::count(),
        'agencies_approved' => \App\Models\Agency::where('status', 'approved')->count(),
        'cars_total' => \App\Models\Car::count(),
        'cars_available' => \App\Models\Car::where('status', 'available')->count(),
        'cars_from_approved_agencies' => \App\Models\Car::where('status', 'available')
            ->whereHas('agency', function($q) { $q->where('status', 'approved'); })
            ->count(),
        'users' => \App\Models\User::select('id', 'name', 'email', 'role')->get(),
        'agencies' => \App\Models\Agency::with('user:id,name,email')->get(),
        'cars' => \App\Models\Car::with(['agency.user:id,name'])->get(),
    ]);
})->middleware('auth');

// Public Routes (No Authentication Required)
Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('public.home');
Route::get('/agencies', [App\Http\Controllers\PublicController::class, 'agencies'])->name('public.agencies');
Route::get('/search', [App\Http\Controllers\PublicController::class, 'search'])->name('public.search');
Route::get('/agencies/{agency}', [App\Http\Controllers\PublicController::class, 'showAgency'])->name('public.agency.show');
Route::get('/agencies/{agency}/cars', [App\Http\Controllers\PublicController::class, 'agencyCars'])->name('public.agency.cars');
Route::get('/agencies/{agency}/cars/{car}', [App\Http\Controllers\PublicController::class, 'showCar'])->name('public.car.show');
Route::get('/require-login', [App\Http\Controllers\PublicController::class, 'requireLogin'])->name('public.require-login');

// Test cancellation system
Route::get('/test/cancellation/{agency_id}', function ($agencyId) {
    $agency = \App\Models\Agency::find($agencyId);
    
    if (!$agency) {
        return response()->json(['error' => 'Agency not found'], 404);
    }
    
    return response()->json([
        'agency_name' => $agency->agency_name,
        'cancellation_count' => $agency->cancellation_count,
        'max_cancellations' => $agency->max_cancellations,
        'is_suspended' => $agency->isSuspended(),
        'can_cancel' => $agency->canCancelBooking(),
        'warning_message' => $agency->getCancellationWarningMessage(),
        'last_cancellation' => $agency->last_cancellation_at,
        'suspended_at' => $agency->suspended_at,
        'suspension_reason' => $agency->suspension_reason
    ]);
})->middleware('auth');


require __DIR__ . '/auth.php';
