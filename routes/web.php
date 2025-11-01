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
use App\Http\Controllers\ReviewController;

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

Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('welcome');
Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('public.home');

// Reviews Routes
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

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
    Route::post('register/agency', [App\Http\Controllers\Auth\AgencyRegisterController::class, 'register'])
        ->name('register.agency.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/activity', [App\Http\Controllers\Admin\DashboardController::class, 'getActivity'])->name('dashboard.activity');
    Route::get('/dashboard/charts', [App\Http\Controllers\Admin\DashboardController::class, 'getCharts'])->name('dashboard.charts');
    
    // Navigation Pages
    Route::get('/users-main', function() { return view('admin.users.index-main'); })->name('users.main');
    Route::get('/vehicles-main', function() { return view('admin.vehicles.index-main'); })->name('vehicles.main');
    Route::get('/bookings-main', function() { return view('admin.bookings.index-main'); })->name('bookings.main');
    // Finance Dashboard
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\FinanceDashboardController::class, 'index'])->name('index');
        Route::post('/export', [App\Http\Controllers\Admin\FinanceDashboardController::class, 'export'])->name('export');
    });
    
    // Commissions Management
    Route::prefix('commissions')->name('commissions.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('index');
        Route::get('/agency/{agencyId}', [App\Http\Controllers\Admin\CommissionController::class, 'agencyDetails'])->name('agency');
        Route::get('/report', [App\Http\Controllers\Admin\CommissionController::class, 'report'])->name('report');
        Route::get('/validate', [App\Http\Controllers\Admin\CommissionController::class, 'validateCalculations'])->name('validate');
        Route::get('/export', [App\Http\Controllers\Admin\CommissionController::class, 'export'])->name('export');
    });
    
    // Competitor Analysis
    Route::prefix('competitors')->name('competitors.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CompetitorController::class, 'index'])->name('index');
        Route::get('/{competitorName}', [App\Http\Controllers\Admin\CompetitorController::class, 'show'])->name('show');
        Route::get('/report/analysis', [App\Http\Controllers\Admin\CompetitorController::class, 'report'])->name('report');
        Route::get('/swot/analysis', [App\Http\Controllers\Admin\CompetitorController::class, 'swot'])->name('swot');
        Route::get('/pricing/benchmark', [App\Http\Controllers\Admin\CompetitorController::class, 'pricingBenchmark'])->name('pricing');
    });
    
    // System Management
    Route::get('/system', function() { return view('admin.system.index'); })->name('system.index');
    
    // Reports
    Route::get('/reports', function() { return view('admin.reports.index'); })->name('reports.index');
    
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
    
    // Featured/Homepage Content Management
    Route::prefix('featured')->name('featured.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\FeaturedContentController::class, 'index'])->name('index');
        
        // Car Management
        Route::post('/cars/{car}/toggle-featured', [App\Http\Controllers\Admin\FeaturedContentController::class, 'toggleCarFeatured'])->name('cars.toggle-featured');
        Route::post('/cars/{car}/toggle-homepage', [App\Http\Controllers\Admin\FeaturedContentController::class, 'toggleCarHomepage'])->name('cars.toggle-homepage');
        Route::put('/cars/{car}/priority', [App\Http\Controllers\Admin\FeaturedContentController::class, 'updateCarPriority'])->name('cars.update-priority');
        Route::post('/cars/bulk-update', [App\Http\Controllers\Admin\FeaturedContentController::class, 'bulkUpdateCars'])->name('cars.bulk-update');
        
        // Agency Management
        Route::post('/agencies/{agency}/toggle-featured', [App\Http\Controllers\Admin\FeaturedContentController::class, 'toggleAgencyFeatured'])->name('agencies.toggle-featured');
        Route::post('/agencies/{agency}/toggle-homepage', [App\Http\Controllers\Admin\FeaturedContentController::class, 'toggleAgencyHomepage'])->name('agencies.toggle-homepage');
        Route::put('/agencies/{agency}/priority', [App\Http\Controllers\Admin\FeaturedContentController::class, 'updateAgencyPriority'])->name('agencies.update-priority');
        Route::post('/agencies/bulk-update', [App\Http\Controllers\Admin\FeaturedContentController::class, 'bulkUpdateAgencies'])->name('agencies.bulk-update');
    });
    
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
    
    // Support & Tickets Management
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SupportController::class, 'index'])->name('index');
        Route::get('/tickets/{ticket}', [App\Http\Controllers\Admin\SupportController::class, 'show'])->name('show');
        Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('reply');
        Route::patch('/tickets/{ticket}/status', [App\Http\Controllers\Admin\SupportController::class, 'updateStatus'])->name('update-status');
        Route::patch('/tickets/{ticket}/priority', [App\Http\Controllers\Admin\SupportController::class, 'updatePriority'])->name('update-priority');
        Route::post('/tickets/{ticket}/assign', [App\Http\Controllers\Admin\SupportController::class, 'assign'])->name('assign');
        Route::delete('/tickets/{ticket}', [App\Http\Controllers\Admin\SupportController::class, 'destroy'])->name('destroy');
        Route::get('/statistics', [App\Http\Controllers\Admin\SupportController::class, 'statistics'])->name('statistics');
        Route::post('/bulk-action', [App\Http\Controllers\Admin\SupportController::class, 'bulkAction'])->name('bulk-action');
        
        // Support Messages
        Route::get('/messages/{ticket}', [App\Http\Controllers\SupportMessageController::class, 'getMessages'])->name('messages');
        Route::post('/messages/{ticket}/send', [App\Http\Controllers\SupportMessageController::class, 'sendMessage'])->name('messages.send');
        Route::post('/messages/{ticket}/mark-read', [App\Http\Controllers\SupportMessageController::class, 'markAsRead'])->name('messages.mark-read');
        Route::get('/unread-count', [App\Http\Controllers\SupportMessageController::class, 'getUnreadCount'])->name('unread-count');
    });
    
    // Admin Messages (Support Tickets)
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('index');
        Route::get('/support/{ticketId}', [App\Http\Controllers\Admin\MessageController::class, 'showSupport'])->name('show-support');
    });
    
    // Admin Support Messages
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/messages/{ticket}', [App\Http\Controllers\Admin\SupportMessageController::class, 'getMessages'])->name('messages.show');
        Route::post('/messages/{ticket}/send', [App\Http\Controllers\Admin\SupportMessageController::class, 'sendMessage'])->name('messages.send');
        Route::post('/messages/{ticket}/mark-read', [App\Http\Controllers\Admin\SupportMessageController::class, 'markAsRead'])->name('messages.mark-read');
        Route::get('/unread-count', [App\Http\Controllers\Admin\SupportMessageController::class, 'getUnreadCount'])->name('unread-count');
    });
    
    // Admin Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{notification}/mark-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-all', [App\Http\Controllers\Admin\NotificationController::class, 'clearAll'])->name('clear-all');
        Route::get('/stats', [App\Http\Controllers\Admin\NotificationController::class, 'getStats'])->name('stats');
    });
    
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
    
    // Support routes accessible to all agencies (including pending)
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/create', [App\Http\Controllers\Agency\SupportController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Agency\SupportController::class, 'store'])->name('store');
        Route::get('/', [App\Http\Controllers\Agency\SupportController::class, 'index'])->name('index');
        Route::get('/tickets/{ticket}', [App\Http\Controllers\Agency\SupportController::class, 'show'])->name('show');
        Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Agency\SupportController::class, 'reply'])->name('reply');
    });

    // Routes only accessible to approved agencies
    Route::middleware('approved.agency')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Agency\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Agency\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [App\Http\Controllers\Agency\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/notifications/{id}', [App\Http\Controllers\Agency\NotificationController::class, 'destroy'])->name('notifications.destroy');
        
        // Fleet Management
        Route::resource('cars', CarController::class);
        Route::put('/cars/{car}/status', [CarController::class, 'updateStatus'])->name('cars.update-status');
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
        
        // Support (Interface unifiée - Messages + Tickets Support)
        
        // Messages (Interface unifiée - Messages clients + Support)
        Route::get('/messages', [App\Http\Controllers\Agency\MessageController::class, 'index'])->name('messages.index')->middleware('mark.online');
        Route::get('/messages/{rental}', [App\Http\Controllers\Agency\MessageController::class, 'show'])->name('messages.show')->middleware('mark.online');
        Route::post('/messages/{rental}', [App\Http\Controllers\Agency\MessageController::class, 'store'])->name('messages.store');
        Route::patch('/messages/{message}/read', [App\Http\Controllers\Agency\MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
        Route::get('/messages/{rental}/new', [App\Http\Controllers\Agency\MessageController::class, 'getNewMessages'])->name('messages.new');
        
        // Support Messages (pour l'interface unifiée)
        Route::get('/support/messages/{ticket}', [App\Http\Controllers\Agency\SupportMessageController::class, 'getMessages'])->name('support.messages.show');
        Route::post('/support/messages/{ticket}/send', [App\Http\Controllers\Agency\SupportMessageController::class, 'sendMessage'])->name('support.messages.send');
        Route::post('/support/messages/{ticket}/mark-read', [App\Http\Controllers\Agency\SupportMessageController::class, 'markAsRead'])->name('support.messages.mark-read');
        Route::get('/support/unread-count', [App\Http\Controllers\Agency\SupportMessageController::class, 'getUnreadCount'])->name('support.unread-count');
        
        // Support & Help
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', [App\Http\Controllers\Agency\SupportController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Agency\SupportController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\Agency\SupportController::class, 'store'])->name('store');
            Route::get('/tickets/{ticket}', [App\Http\Controllers\Agency\SupportController::class, 'show'])->name('show');
            Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Agency\SupportController::class, 'reply'])->name('reply');
            Route::patch('/tickets/{ticket}/resolve', [App\Http\Controllers\Agency\SupportController::class, 'markResolved'])->name('resolve');
            Route::patch('/tickets/{ticket}/reopen', [App\Http\Controllers\Agency\SupportController::class, 'reopen'])->name('reopen');
            Route::get('/tickets', [App\Http\Controllers\Agency\SupportController::class, 'getTickets'])->name('tickets');
        });
        
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
    
    // Rental management (Old system - keep for backward compatibility)
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
        
        // Messages
        Route::get('/messages', [App\Http\Controllers\Client\MessageController::class, 'index'])->name('messages.index')->middleware('mark.online');
        Route::get('/messages/{rental}', [App\Http\Controllers\Client\MessageController::class, 'show'])->name('messages.show')->middleware('mark.online');
        Route::post('/messages/{rental}', [App\Http\Controllers\Client\MessageController::class, 'store'])->name('messages.store');
        Route::patch('/messages/{message}/read', [App\Http\Controllers\Client\MessageController::class, 'markAsRead'])->name('messages.mark-as-read');
        Route::get('/messages/{rental}/new', [App\Http\Controllers\Client\MessageController::class, 'getNewMessages'])->name('messages.new');
        
        // Notifications
        Route::get('/notifications', [App\Http\Controllers\Client\NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::patch('/notifications/read-all', [App\Http\Controllers\Client\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{notification}', [App\Http\Controllers\Client\NotificationController::class, 'destroy'])->name('notifications.destroy');
        
        // Support & Help
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', [App\Http\Controllers\Client\SupportController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Client\SupportController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\Client\SupportController::class, 'store'])->name('store');
            Route::get('/tickets/{ticket}', [App\Http\Controllers\Client\SupportController::class, 'show'])->name('show');
            Route::post('/tickets/{ticket}/reply', [App\Http\Controllers\Client\SupportController::class, 'reply'])->name('reply');
            Route::patch('/tickets/{ticket}/resolve', [App\Http\Controllers\Client\SupportController::class, 'markResolved'])->name('resolve');
            Route::patch('/tickets/{ticket}/reopen', [App\Http\Controllers\Client\SupportController::class, 'reopen'])->name('reopen');
            Route::get('/contact', [App\Http\Controllers\Client\SupportController::class, 'contact'])->name('contact');
            Route::post('/contact', [App\Http\Controllers\Client\SupportController::class, 'storeContact'])->name('contact.store');
            
            // Support Messages
            Route::get('/messages', [App\Http\Controllers\Client\SupportController::class, 'messages'])->name('messages');
            Route::get('/tickets', [App\Http\Controllers\Client\SupportController::class, 'getTickets'])->name('tickets');
            Route::get('/messages/{ticket}', [App\Http\Controllers\Client\SupportMessageController::class, 'getMessages'])->name('messages.show');
            Route::post('/messages/{ticket}/send', [App\Http\Controllers\Client\SupportMessageController::class, 'sendMessage'])->name('messages.send');
            Route::post('/messages/{ticket}/mark-read', [App\Http\Controllers\Client\SupportMessageController::class, 'markAsRead'])->name('messages.mark-read');
            Route::get('/unread-count', [App\Http\Controllers\Client\SupportMessageController::class, 'getUnreadCount'])->name('unread-count');
        });
});

// New booking system (Airbnb-style multi-step) - Public routes
Route::prefix('booking')->name('booking.')->group(function () {
    // Booking page (Airbnb-style design)
    Route::get('/{car}', function(\App\Models\Car $car) {
        return view('client.booking.main', compact('car'));
    })->name('main');
    
    // Step 1: Date selection (public)
    Route::get('/{car}/step1', [App\Http\Controllers\Client\BookingController::class, 'step1'])->name('step1');
    Route::post('/{car}/step1', [App\Http\Controllers\Client\BookingController::class, 'processStep1'])->name('process-step1');
    
    // Step 2: Login (public)
    Route::get('/step2', [App\Http\Controllers\Client\BookingController::class, 'step2'])->name('step2');
    
    // Steps 3-5: Require authentication
    Route::middleware(['auth', 'client'])->group(function () {
        Route::get('/{car}/review', function(\App\Models\Car $car) {
            return view('client.booking.review', compact('car'));
        })->name('review');
        
        Route::get('/step3', [App\Http\Controllers\Client\BookingController::class, 'step3'])->name('step3');
        Route::get('/step4', [App\Http\Controllers\Client\BookingController::class, 'step4'])->name('step4');
        Route::post('/process-payment', [App\Http\Controllers\Client\BookingController::class, 'processPayment'])->name('process-payment');
        Route::get('/step5', [App\Http\Controllers\Client\BookingController::class, 'step5'])->name('step5');
        Route::post('/cancel', [App\Http\Controllers\Client\BookingController::class, 'cancel'])->name('cancel');
    });
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
Route::get('/agencies', [App\Http\Controllers\PublicController::class, 'agencies'])->name('public.agencies');
Route::get('/about', [App\Http\Controllers\PublicController::class, 'about'])->name('public.about');
Route::get('/how-it-works', [App\Http\Controllers\PublicController::class, 'howItWorks'])->name('public.how-it-works');
Route::get('/search', [App\Http\Controllers\PublicController::class, 'search'])->name('public.search');
Route::get('/cars/search', [App\Http\Controllers\PublicController::class, 'searchCars'])->name('public.cars.search');
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
