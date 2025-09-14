<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        
        // Get basic statistics
        $stats = [
            'total_revenue' => Rental::where('rentals.agency_id', $agency->id)
                ->whereIn('rentals.status', ['active', 'completed'])
                ->sum('total_price'),
            'total_rentals' => Rental::where('rentals.agency_id', $agency->id)->count(),
            'active_rentals' => Rental::where('rentals.agency_id', $agency->id)
                ->where('rentals.status', 'active')->count(),
            'total_cars' => Car::where('cars.agency_id', $agency->id)->count(),
        ];
        
        return view('agence.reports.index', compact('stats'));
    }
    
    public function performance()
    {
        $agency = auth()->user()->agency;
        
        // Get performance data for the last 12 months
        $performanceData = $this->getPerformanceData($agency);
        
        return view('agence.reports.performance', compact('performanceData'));
    }
    
    public function revenue()
    {
        $agency = auth()->user()->agency;
        
        // Get revenue data
        $revenueData = $this->getRevenueData($agency);
        
        // Calculate revenue metrics
        $totalRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->sum('total_price');
            
        $currentMonthRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereMonth('rentals.created_at', now()->month)
            ->whereYear('rentals.created_at', now()->year)
            ->sum('total_price');
            
        $lastMonthRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->whereMonth('rentals.created_at', now()->subMonth()->month)
            ->whereYear('rentals.created_at', now()->subMonth()->year)
            ->sum('total_price');
            
        $revenueGrowth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        
        // Get revenue by car type
        $revenueByCarType = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->join('categories', 'cars.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('SUM(rentals.total_price) as revenue'))
            ->groupBy('categories.name')
            ->get();
            
        // Get top performing cars
        $topCars = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->select('cars.brand', 'cars.model', 'cars.year', DB::raw('SUM(rentals.total_price) as revenue'), DB::raw('COUNT(*) as bookings'))
            ->groupBy('cars.id', 'cars.brand', 'cars.model', 'cars.year')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();
        
        // Commission analysis (assuming 10% commission rate)
        $commissionRate = 10;
        $totalCommissionPaid = $totalRevenue * ($commissionRate / 100);
        
        $revenue = [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $currentMonthRevenue,
            'revenue_growth' => $revenueGrowth,
            'revenue_by_car_type' => $revenueByCarType,
            'top_cars' => $topCars
        ];
        
        $insights = [
            'commission_analysis' => [
                'total_commission_paid' => $totalCommissionPaid,
                'current_rate' => $commissionRate
            ]
        ];
        
        return view('agence.reports.revenue', compact('revenue', 'insights', 'revenueData'));
    }
    
    public function customers()
    {
        $agency = auth()->user()->agency;
        
        // Get customer statistics
        $customerStats = [
            'total_customers' => Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->count(),
            'active_customers' => Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id)
                      ->whereIn('status', ['active', 'completed']);
            })->count(),
            'new_customers_this_month' => Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id)
                      ->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            })->count(),
            'repeat_customers' => Client::whereHas('rentals', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->withCount(['rentals' => function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            }])->having('rentals_count', '>', 1)->count(),
        ];
        
        // Get top customers by revenue
        $topCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('agency_id', $agency->id)
                  ->whereIn('status', ['active', 'completed']);
        })
        ->with(['rentals' => function($query) use ($agency) {
            $query->where('agency_id', $agency->id)
                  ->whereIn('status', ['active', 'completed']);
        }])
        ->get()
        ->map(function($client) {
            $client->total_revenue = $client->rentals->sum('total_price');
            $client->total_rentals = $client->rentals->count();
            return $client;
        })
        ->sortByDesc('total_revenue')
        ->take(10);
        
        // Get customer satisfaction data (if reviews exist)
        $satisfactionData = [
            'average_rating' => 4.2, // Placeholder - would come from reviews table
            'total_reviews' => 0, // Placeholder
            'satisfaction_score' => 85, // Placeholder
        ];
        
        // Get customer acquisition data
        $acquisitionData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $newCustomers = Client::whereHas('rentals', function($query) use ($agency, $month) {
                $query->where('agency_id', $agency->id)
                      ->whereMonth('created_at', $month->month)
                      ->whereYear('created_at', $month->year);
            })->count();
            
            $acquisitionData[] = [
                'month' => $month->format('M Y'),
                'new_customers' => $newCustomers
            ];
        }
        
        return view('agence.reports.customers', compact('customerStats', 'topCustomers', 'satisfactionData', 'acquisitionData'));
    }
    
    public function fleet()
    {
        $agency = auth()->user()->agency;
        
        // Get fleet statistics
        $fleetStats = [
            'total_vehicles' => Car::where('cars.agency_id', $agency->id)->count(),
            'available_vehicles' => Car::where('cars.agency_id', $agency->id)
                ->where('cars.status', 'available')->count(),
            'rented_vehicles' => Car::where('cars.agency_id', $agency->id)
                ->whereHas('rentals', function($query) {
                    $query->whereIn('status', ['active']);
                })->count(),
            'maintenance_vehicles' => Car::where('cars.agency_id', $agency->id)
                ->where('cars.status', 'maintenance')->count(),
        ];
        
        // Get utilization data
        $utilizationData = Car::where('cars.agency_id', $agency->id)
            ->withCount(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->with(['rentals' => function($query) {
                $query->whereIn('status', ['active', 'completed']);
            }])
            ->get()
            ->map(function($car) {
                $car->total_revenue = $car->rentals->sum('total_price');
                $car->utilization_rate = $car->rentals_count > 0 ? min(100, ($car->rentals_count / 30) * 100) : 0;
                return $car;
            })
            ->sortByDesc('total_revenue');
        
        // Get category performance
        $categoryPerformance = Car::where('cars.agency_id', $agency->id)
            ->join('categories', 'cars.category_id', '=', 'categories.id')
            ->leftJoin('rentals', function($join) {
                $join->on('cars.id', '=', 'rentals.car_id')
                     ->whereIn('rentals.status', ['active', 'completed']);
            })
            ->select('categories.name as category_name', 
                    DB::raw('COUNT(DISTINCT cars.id) as total_cars'),
                    DB::raw('COUNT(rentals.id) as total_rentals'),
                    DB::raw('COALESCE(SUM(rentals.total_price), 0) as total_revenue'))
            ->groupBy('categories.name')
            ->get();
        
        return view('agence.reports.fleet', compact('fleetStats', 'utilizationData', 'categoryPerformance'));
    }
    
    public function exportPerformanceCSV()
    {
        $agency = auth()->user()->agency;
        $performanceData = $this->getPerformanceData($agency);
        
        $filename = 'rapport_performance_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($performanceData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, ['Mois', 'Revenus (MAD)', 'Locations', 'Taux d\'Occupation (%)', 'Revenus Moyens (MAD)']);
            
            // Data
            foreach ($performanceData['monthly_data'] as $month) {
                fputcsv($file, [
                    $month['month'],
                    $month['revenue'],
                    $month['rentals'],
                    $month['occupancy_rate'],
                    $month['average_revenue']
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function exportPerformancePDF()
    {
        $agency = auth()->user()->agency;
        $performanceData = $this->getPerformanceData($agency);
        
        // For now, we'll return a view that can be printed as PDF
        // In a real application, you'd use a library like DomPDF or TCPDF
        return view('agence.reports.performance-pdf', compact('performanceData', 'agency'));
    }
    
    private function getPerformanceData($agency)
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Get monthly data
        $monthlyData = [];
        for ($i = 0; $i < 12; $i++) {
            $monthStart = $startDate->copy()->addMonths($i);
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            $monthRentals = Rental::where('rentals.agency_id', $agency->id)
                ->whereBetween('rentals.created_at', [$monthStart, $monthEnd])
                ->get();
            
            $revenue = $monthRentals->whereIn('status', ['active', 'completed'])->sum('total_price');
            $rentals = $monthRentals->count();
            $totalCars = Car::where('cars.agency_id', $agency->id)->count();
            $occupancyRate = $totalCars > 0 ? ($rentals / ($totalCars * 30)) * 100 : 0;
            $averageRevenue = $rentals > 0 ? $revenue / $rentals : 0;
            
            $monthlyData[] = [
                'month' => $monthStart->format('M Y'),
                'revenue' => $revenue,
                'rentals' => $rentals,
                'occupancy_rate' => $occupancyRate,
                'average_revenue' => $averageRevenue
            ];
        }
        
        // Calculate totals
        $totalRevenue = collect($monthlyData)->sum('revenue');
        $totalRentals = collect($monthlyData)->sum('rentals');
        $averageOccupancyRate = collect($monthlyData)->avg('occupancy_rate');
        $averageRevenue = $totalRentals > 0 ? $totalRevenue / $totalRentals : 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'total_rentals' => $totalRentals,
            'occupancy_rate' => $averageOccupancyRate,
            'average_revenue' => $averageRevenue,
            'monthly_data' => $monthlyData
        ];
    }
    
    private function getRevenueData($agency)
    {
        // Get revenue data for charts and analysis
        $revenueData = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('rentals.status', ['active', 'completed'])
            ->select(
                DB::raw('DATE(rentals.created_at) as date'),
                DB::raw('SUM(rentals.total_price) as daily_revenue'),
                DB::raw('COUNT(*) as daily_rentals')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return $revenueData;
    }
}