<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Agency;
use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with(['user.client', 'car.agency', 'agency']);

        // Advanced filtering
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('agency_id') && $request->agency_id !== '') {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhereHas('car', function($carQuery) use ($search) {
                    $carQuery->where('brand', 'like', '%' . $search . '%')
                            ->orWhere('model', 'like', '%' . $search . '%')
                            ->orWhere('registration_number', 'like', '%' . $search . '%');
                })
                ->orWhereHas('agency', function($agencyQuery) use ($search) {
                    $agencyQuery->where('agency_name', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->has('price_min') && $request->price_min !== '') {
            $query->where('total_price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max !== '') {
            $query->where('total_price', '<=', $request->price_max);
        }

        $bookings = $query->latest()->paginate(15);

        // Get statistics
        $statistics = [
            'total' => Rental::count(),
            'pending' => Rental::where('status', 'pending')->count(),
            'active' => Rental::where('status', 'active')->count(),
            'completed' => Rental::where('status', 'completed')->count(),
            'cancelled' => Rental::where('status', 'rejected')->count(),
            'totalRevenue' => Rental::whereIn('status', ['active', 'completed'])->sum('total_price'),
            'monthlyRevenue' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
        ];

        // Get agencies for filter dropdown
        $agencies = Agency::where('status', 'approved')->orderBy('agency_name')->get();

        return view('admin.bookings.index', compact('bookings', 'statistics', 'agencies'));
    }

    public function active(Request $request)
    {
        $query = Rental::with(['user', 'car.agency', 'agency'])
            ->where('status', 'active');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhereHas('car', function($carQuery) use ($search) {
                    $carQuery->where('brand', 'like', '%' . $search . '%')
                            ->orWhere('model', 'like', '%' . $search . '%');
                });
            });
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.active', compact('bookings'));
    }

    public function calendar(Request $request)
    {
        $startDate = $request->get('start', Carbon::now()->startOfMonth());
        $endDate = $request->get('end', Carbon::now()->endOfMonth());

        $bookings = Rental::with(['user', 'car', 'agency'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->car->brand . ' ' . $booking->car->model . ' - ' . $booking->user->name,
                    'start' => $booking->start_date->format('Y-m-d'),
                    'end' => $booking->end_date->addDay()->format('Y-m-d'),
                    'status' => $booking->status,
                    'color' => $this->getStatusColor($booking->status),
                    'url' => route('admin.bookings.show', $booking),
                    'car' => [
                        'brand' => $booking->car->brand,
                        'model' => $booking->car->model
                    ],
                    'user' => [
                        'name' => $booking->user->name
                    ]
                ];
            });

        // Get statistics for the calendar view
        $statistics = [
            'total' => Rental::count(),
            'pending' => Rental::where('status', 'pending')->count(),
            'active' => Rental::where('status', 'active')->count(),
            'completed' => Rental::where('status', 'completed')->count(),
            'cancelled' => Rental::where('status', 'rejected')->count(),
            'totalRevenue' => Rental::whereIn('status', ['active', 'completed'])->sum('total_price'),
            'monthlyRevenue' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
        ];

        return view('admin.bookings.calendar', compact('bookings', 'statistics'));
    }

    public function show(Rental $booking)
    {
        $booking->load(['user', 'car.agency', 'agency', 'transactions']);

        // Get booking timeline
        $timeline = [
            [
                'date' => $booking->created_at,
                'event' => 'Réservation créée',
                'description' => 'La réservation a été créée par le client',
                'status' => 'completed'
            ],
            [
                'date' => $booking->start_date,
                'event' => 'Début de location',
                'description' => 'La location commence',
                'status' => $booking->start_date <= now() ? 'completed' : 'pending'
            ],
            [
                'date' => $booking->end_date,
                'event' => 'Fin de location',
                'description' => 'La location se termine',
                'status' => $booking->end_date <= now() ? 'completed' : 'pending'
            ]
        ];

        // Get related bookings
        $relatedBookings = Rental::where('user_id', $booking->user_id)
            ->where('id', '!=', $booking->id)
            ->with(['car', 'agency'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.bookings.show', compact('booking', 'timeline', 'relatedBookings'));
    }

    public function updateStatus(Request $request, Rental $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,active,completed,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        // Log the status change
        \Log::info("Booking {$booking->id} status changed from {$oldStatus} to {$request->status} by admin " . auth()->user()->name);

        // If booking is completed, update car status
        if ($request->status === 'completed') {
            $booking->car->update(['status' => 'available']);
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Le statut de la réservation a été mis à jour avec succès.');
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', '12months');
        
        // Get booking trends
        $bookingTrends = $this->getBookingTrends($period);
        
        // Get revenue analytics
        $revenueAnalytics = $this->getRevenueAnalytics($period);
        
        // Get popular car categories
        $popularCategories = $this->getPopularCategories($period);
        
        // Get agency performance
        $agencyPerformance = $this->getAgencyPerformance($period);
        
        // Get cancellation analytics
        $cancellationAnalytics = $this->getCancellationAnalytics($period);

        return view('admin.bookings.analytics', compact(
            'bookingTrends',
            'revenueAnalytics', 
            'popularCategories',
            'agencyPerformance',
            'cancellationAnalytics'
        ));
    }

    public function export(Request $request)
    {
        $query = Rental::with(['user', 'car', 'agency']);

        // Apply same filters as index
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $bookings = $query->get();

        $filename = 'bookings_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Client', 'Email', 'Véhicule', 'Agence', 'Date début', 
                'Date fin', 'Prix total', 'Statut', 'Date création'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->user->name,
                    $booking->user->email,
                    $booking->car->brand . ' ' . $booking->car->model,
                    $booking->agency->agency_name ?? 'N/A',
                    $booking->start_date->format('Y-m-d'),
                    $booking->end_date->format('Y-m-d'),
                    $booking->total_price,
                    $booking->status,
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
                return '#f59e0b';
            case 'active':
                return '#10b981';
            case 'completed':
                return '#3b82f6';
            case 'rejected':
                return '#ef4444';
            default:
                return '#6b7280';
        }
    }

    private function getBookingTrends($period)
    {
        $data = [];
        $labels = [];

        if ($period === '12months') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $data[] = Rental::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();
            }
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Rental::whereDate('created_at', $date)->count();
            }
        }

        return ['data' => $data, 'labels' => $labels];
    }

    private function getRevenueAnalytics($period)
    {
        $data = [];
        $labels = [];

        if ($period === '12months') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $data[] = Rental::whereIn('status', ['active', 'completed'])
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_price');
            }
        } else {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Rental::whereIn('status', ['active', 'completed'])
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
            }
        }

        return ['data' => $data, 'labels' => $labels];
    }

    private function getPopularCategories($period)
    {
        $query = Rental::join('cars', 'rentals.car_id', '=', 'cars.id')
            ->select('cars.brand', DB::raw('count(*) as count'))
            ->groupBy('cars.brand')
            ->orderBy('count', 'desc')
            ->take(10);

        if ($period === '12months') {
            $query->where('rentals.created_at', '>=', Carbon::now()->subYear());
        } else {
            $query->where('rentals.created_at', '>=', Carbon::now()->subMonth());
        }

        return $query->get();
    }

    private function getAgencyPerformance($period)
    {
        $query = Rental::join('agencies', 'rentals.agency_id', '=', 'agencies.id')
            ->select('agencies.agency_name', 
                DB::raw('count(*) as total_bookings'),
                DB::raw('sum(total_price) as total_revenue'),
                DB::raw('avg(total_price) as avg_booking_value')
            )
            ->groupBy('agencies.id', 'agencies.agency_name')
            ->orderBy('total_revenue', 'desc')
            ->take(10);

        if ($period === '12months') {
            $query->where('rentals.created_at', '>=', Carbon::now()->subYear());
        } else {
            $query->where('rentals.created_at', '>=', Carbon::now()->subMonth());
        }

        return $query->get();
    }

    private function getCancellationAnalytics($period)
    {
        $query = Rental::where('status', 'rejected');

        if ($period === '12months') {
            $query->where('created_at', '>=', Carbon::now()->subYear());
        } else {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        }

        return [
            'total_cancellations' => $query->count(),
            'cancellation_rate' => $this->getCancellationRate($period),
            'monthly_cancellations' => $this->getMonthlyCancellations($period)
        ];
    }

    private function getCancellationRate($period)
    {
        $totalBookings = Rental::count();
        $cancelledBookings = Rental::where('status', 'rejected')->count();
        
        return $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 2) : 0;
    }

    private function getMonthlyCancellations($period)
    {
        $data = [];
        $labels = [];

        if ($period === '12months') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');
                $data[] = Rental::where('status', 'rejected')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();
            }
        }

        return ['data' => $data, 'labels' => $labels];
    }
}
