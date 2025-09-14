<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Get overview statistics for reports
        $overview = [
            'totalAgencies' => Agency::count(),
            'totalCustomers' => Client::count(),
            'totalVehicles' => Car::count(),
            'totalBookings' => Rental::count(),
            'totalRevenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->sum('amount'),
            'monthlyRevenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                ->where('status', Transaction::STATUS_COMPLETED)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
        ];

        // Get recent report activity (this would come from a reports table)
        $recentReports = collect(); // Placeholder for report history

        return view('admin.reports.index', compact('overview', 'recentReports'));
    }

    public function custom(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->generateCustomReport($request);
        }

        // Get available data sources for custom reports
        $dataSources = [
            'agencies' => 'Agences',
            'customers' => 'Clients',
            'vehicles' => 'Véhicules',
            'bookings' => 'Réservations',
            'transactions' => 'Transactions',
        ];

        // Get available date ranges
        $dateRanges = [
            'today' => 'Aujourd\'hui',
            'yesterday' => 'Hier',
            'this_week' => 'Cette semaine',
            'last_week' => 'Semaine dernière',
            'this_month' => 'Ce mois',
            'last_month' => 'Mois dernier',
            'this_quarter' => 'Ce trimestre',
            'last_quarter' => 'Trimestre dernier',
            'this_year' => 'Cette année',
            'last_year' => 'Année dernière',
            'custom' => 'Période personnalisée',
        ];

        return view('admin.reports.custom', compact('dataSources', 'dateRanges'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'data_type' => 'required|in:agencies,customers,vehicles,bookings,transactions',
            'format' => 'required|in:csv,excel,pdf',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'filters' => 'nullable|array',
        ]);

        $dataType = $request->data_type;
        $format = $request->format;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        switch ($dataType) {
            case 'agencies':
                return $this->exportAgencies($format, $dateFrom, $dateTo, $request->filters ?? []);
            case 'customers':
                return $this->exportCustomers($format, $dateFrom, $dateTo, $request->filters ?? []);
            case 'vehicles':
                return $this->exportVehicles($format, $dateFrom, $dateTo, $request->filters ?? []);
            case 'bookings':
                return $this->exportBookings($format, $dateFrom, $dateTo, $request->filters ?? []);
            case 'transactions':
                return $this->exportTransactions($format, $dateFrom, $dateTo, $request->filters ?? []);
            default:
                return redirect()->back()->with('error', 'Type de données non supporté.');
        }
    }

    public function performance(Request $request)
    {
        $period = $request->get('period', '12months');
        
        // Platform performance metrics
        $platformMetrics = $this->getPlatformMetrics($period);
        
        // Agency performance comparison
        $agencyPerformance = $this->getAgencyPerformance($period);
        
        // Customer analytics
        $customerAnalytics = $this->getCustomerAnalytics($period);
        
        // Vehicle utilization
        $vehicleUtilization = $this->getVehicleUtilization($period);
        
        // Revenue analysis
        $revenueAnalysis = $this->getRevenueAnalysis($period);

        return view('admin.reports.performance', compact(
            'platformMetrics',
            'agencyPerformance',
            'customerAnalytics',
            'vehicleUtilization',
            'revenueAnalysis'
        ));
    }

    public function audit(Request $request)
    {
        // This would integrate with an audit log system
        // For now, we'll show a placeholder with some basic activity
        
        $auditLogs = collect(); // This would come from an AuditLog model
        
        // Get system activity statistics
        $auditStats = [
            'totalLogs' => 0, // This would be AuditLog::count()
            'todayLogs' => 0,
            'adminActions' => 0,
            'systemEvents' => 0,
        ];

        return view('admin.reports.audit', compact('auditLogs', 'auditStats'));
    }

    private function generateCustomReport(Request $request)
    {
        $request->validate([
            'report_name' => 'required|string|max:255',
            'data_sources' => 'required|array',
            'date_range' => 'required|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'filters' => 'nullable|array',
        ]);

        // Generate the custom report based on parameters
        $reportData = $this->buildCustomReportData($request);
        
        // Save report configuration (this would be saved to a reports table)
        // Report::create([...]);
        
        return view('admin.reports.custom-result', compact('reportData'));
    }

    private function buildCustomReportData(Request $request)
    {
        $dataSources = $request->data_sources;
        $dateRange = $request->date_range;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $filters = $request->filters ?? [];

        $reportData = [];

        foreach ($dataSources as $source) {
            switch ($source) {
                case 'agencies':
                    $reportData['agencies'] = $this->getAgencyReportData($dateRange, $dateFrom, $dateTo, $filters);
                    break;
                case 'customers':
                    $reportData['customers'] = $this->getCustomerReportData($dateRange, $dateFrom, $dateTo, $filters);
                    break;
                case 'vehicles':
                    $reportData['vehicles'] = $this->getVehicleReportData($dateRange, $dateFrom, $dateTo, $filters);
                    break;
                case 'bookings':
                    $reportData['bookings'] = $this->getBookingReportData($dateRange, $dateFrom, $dateTo, $filters);
                    break;
                case 'transactions':
                    $reportData['transactions'] = $this->getTransactionReportData($dateRange, $dateFrom, $dateTo, $filters);
                    break;
            }
        }

        return $reportData;
    }

    private function getAgencyReportData($dateRange, $dateFrom, $dateTo, $filters)
    {
        $query = Agency::with(['user', 'cars', 'rentals']);

        $this->applyDateRange($query, $dateRange, $dateFrom, $dateTo);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['city'])) {
            $query->where('city', 'like', '%' . $filters['city'] . '%');
        }

        return $query->get();
    }

    private function getCustomerReportData($dateRange, $dateFrom, $dateTo, $filters)
    {
        $query = Client::with(['user', 'rentals']);

        $this->applyDateRange($query, $dateRange, $dateFrom, $dateTo);

        if (isset($filters['has_bookings'])) {
            if ($filters['has_bookings'] === 'yes') {
                $query->has('rentals');
            } else {
                $query->doesntHave('rentals');
            }
        }

        return $query->get();
    }

    private function getVehicleReportData($dateRange, $dateFrom, $dateTo, $filters)
    {
        $query = Car::with(['agency', 'rentals']);

        $this->applyDateRange($query, $dateRange, $dateFrom, $dateTo);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['brand'])) {
            $query->where('brand', 'like', '%' . $filters['brand'] . '%');
        }

        return $query->get();
    }

    private function getBookingReportData($dateRange, $dateFrom, $dateTo, $filters)
    {
        $query = Rental::with(['user', 'car', 'agency']);

        $this->applyDateRange($query, $dateRange, $dateFrom, $dateTo);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['agency_id'])) {
            $query->where('agency_id', $filters['agency_id']);
        }

        return $query->get();
    }

    private function getTransactionReportData($dateRange, $dateFrom, $dateTo, $filters)
    {
        $query = Transaction::with(['agency', 'rental']);

        $this->applyDateRange($query, $dateRange, $dateFrom, $dateTo);

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    private function applyDateRange($query, $dateRange, $dateFrom, $dateTo)
    {
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                break;
            case 'this_quarter':
                $query->whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
                break;
            case 'last_quarter':
                $query->whereBetween('created_at', [Carbon::now()->subQuarter()->startOfQuarter(), Carbon::now()->subQuarter()->endOfQuarter()]);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_year':
                $query->whereYear('created_at', Carbon::now()->subYear()->year);
                break;
            case 'custom':
                if ($dateFrom) {
                    $query->whereDate('created_at', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->whereDate('created_at', '<=', $dateTo);
                }
                break;
        }
    }

    private function getPlatformMetrics($period)
    {
        $months = $period === '12months' ? 12 : 6;
        
        $metrics = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $metrics[] = [
                'month' => $date->format('M Y'),
                'new_agencies' => Agency::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
                'new_customers' => Client::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
                'new_bookings' => Rental::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
                'revenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                    ->where('status', Transaction::STATUS_COMPLETED)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount'),
            ];
        }

        return $metrics;
    }

    private function getAgencyPerformance($period)
    {
        $months = $period === '12months' ? 12 : 6;
        
        return Agency::where('status', 'approved')
            ->withSum(['transactions' => function($query) use ($months) {
                $query->where('type', Transaction::TYPE_RENTAL_PAYMENT)
                      ->where('status', Transaction::STATUS_COMPLETED)
                      ->where('created_at', '>=', Carbon::now()->subMonths($months));
            }], 'amount')
            ->withCount(['rentals' => function($query) use ($months) {
                $query->where('created_at', '>=', Carbon::now()->subMonths($months));
            }])
            ->orderBy('transactions_sum_amount', 'desc')
            ->take(20)
            ->get();
    }

    private function getCustomerAnalytics($period)
    {
        $months = $period === '12months' ? 12 : 6;
        
        // Get customers with rental counts
        $customersWithRentals = Client::withCount(['rentals' => function($query) use ($months) {
            $query->where('created_at', '>=', Carbon::now()->subMonths($months));
        }])->get();
        
        $averageBookings = $customersWithRentals->avg('rentals_count');
        
        return [
            'total_customers' => Client::count(),
            'new_customers' => Client::where('created_at', '>=', Carbon::now()->subMonths($months))->count(),
            'active_customers' => Client::whereHas('rentals', function($query) use ($months) {
                $query->where('created_at', '>=', Carbon::now()->subMonths($months));
            })->count(),
            'average_bookings_per_customer' => round($averageBookings, 2),
        ];
    }

    private function getVehicleUtilization($period)
    {
        $months = $period === '12months' ? 12 : 6;
        
        return [
            'total_vehicles' => Car::count(),
            'available_vehicles' => Car::where('status', 'available')->count(),
            'rented_vehicles' => Car::where('status', 'rented')->count(),
            'utilization_rate' => $this->calculateUtilizationRate($months),
            'top_performing_vehicles' => Car::withCount(['rentals' => function($query) use ($months) {
                $query->where('created_at', '>=', Carbon::now()->subMonths($months));
            }])->orderBy('rentals_count', 'desc')->take(10)->get(),
        ];
    }

    private function getRevenueAnalysis($period)
    {
        $months = $period === '12months' ? 12 : 6;
        
        $revenueData = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueData[] = [
                'month' => $date->format('M Y'),
                'revenue' => Transaction::where('type', Transaction::TYPE_RENTAL_PAYMENT)
                    ->where('status', Transaction::STATUS_COMPLETED)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount'),
                'bookings' => Rental::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
            ];
        }

        return $revenueData;
    }

    private function calculateUtilizationRate($months)
    {
        $totalDays = $months * 30;
        $rentedDays = Rental::whereIn('status', ['active', 'completed'])
            ->where('created_at', '>=', Carbon::now()->subMonths($months))
            ->get()
            ->sum(function($rental) {
                return $rental->start_date->diffInDays($rental->end_date);
            });

        $totalVehicleDays = Car::count() * $totalDays;
        
        return $totalVehicleDays > 0 ? round(($rentedDays / $totalVehicleDays) * 100, 2) : 0;
    }

    // Export methods for different data types
    private function exportAgencies($format, $dateFrom, $dateTo, $filters)
    {
        // Implementation for agency export
        return response()->json(['message' => 'Agency export not implemented yet']);
    }

    private function exportCustomers($format, $dateFrom, $dateTo, $filters)
    {
        // Implementation for customer export
        return response()->json(['message' => 'Customer export not implemented yet']);
    }

    private function exportVehicles($format, $dateFrom, $dateTo, $filters)
    {
        // Implementation for vehicle export
        return response()->json(['message' => 'Vehicle export not implemented yet']);
    }

    private function exportBookings($format, $dateFrom, $dateTo, $filters)
    {
        // Implementation for booking export
        return response()->json(['message' => 'Booking export not implemented yet']);
    }

    private function exportTransactions($format, $dateFrom, $dateTo, $filters)
    {
        // Implementation for transaction export
        return response()->json(['message' => 'Transaction export not implemented yet']);
    }
}
