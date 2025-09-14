<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Rental;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['user', 'rentals']);

        // Advanced filtering
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('cin', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('city') && $request->city !== '') {
            $query->where('address', 'like', '%' . $request->city . '%');
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('has_bookings') && $request->has_bookings !== '') {
            if ($request->has_bookings === 'yes') {
                $query->has('rentals');
            } else {
                $query->doesntHave('rentals');
            }
        }

        $customers = $query->latest()->paginate(15);

        // Get statistics
        $statistics = [
            'total' => Client::count(),
            'withBookings' => Client::has('rentals')->count(),
            'withoutBookings' => Client::doesntHave('rentals')->count(),
            'newThisMonth' => Client::whereMonth('created_at', Carbon::now()->month)->count(),
            'totalBookings' => Rental::count(),
            'activeBookings' => Rental::where('status', 'active')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'statistics'));
    }

    public function profiles(Request $request)
    {
        $query = Client::with(['user', 'rentals.car', 'rentals.agency']);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('cin', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $customers = $query->latest()->paginate(12);

        return view('admin.customers.profiles', compact('customers'));
    }

    public function show(Client $customer)
    {
        $customer->load(['user', 'rentals.car', 'rentals.agency']);

        // Get customer statistics
        $statistics = [
            'totalBookings' => $customer->rentals()->count(),
            'activeBookings' => $customer->rentals()->where('status', 'active')->count(),
            'completedBookings' => $customer->rentals()->where('status', 'completed')->count(),
            'cancelledBookings' => $customer->rentals()->where('status', 'rejected')->count(),
            'totalSpent' => $customer->rentals()->whereIn('status', ['active', 'completed'])->sum('total_price'),
            'averageBookingValue' => $customer->rentals()->whereIn('status', ['active', 'completed'])->avg('total_price'),
            'favoriteCarBrand' => $this->getFavoriteCarBrand($customer),
            'memberSince' => $customer->created_at->diffForHumans(),
        ];

        // Get booking history with pagination
        $bookingHistory = $customer->rentals()
            ->with(['car', 'agency'])
            ->latest()
            ->paginate(10);

        // Get monthly booking trends
        $monthlyBookings = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $monthlyBookings[] = $customer->rentals()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return view('admin.customers.show', compact('customer', 'statistics', 'bookingHistory', 'monthlyBookings', 'labels'));
    }

    public function bookingHistory(Client $customer)
    {
        $customer->load(['user']);

        $query = $customer->rentals()->with(['car', 'agency']);

        // Filter by status
        if (request()->has('status') && request()->status !== '') {
            $query->where('status', request()->status);
        }

        // Filter by date range
        if (request()->has('date_from') && request()->date_from !== '') {
            $query->whereDate('created_at', '>=', request()->date_from);
        }

        if (request()->has('date_to') && request()->date_to !== '') {
            $query->whereDate('created_at', '<=', request()->date_to);
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.customers.booking-history', compact('customer', 'bookings'));
    }

    public function supportTickets(Request $request)
    {
        // This would integrate with a support ticket system
        // For now, we'll show a placeholder
        $tickets = collect(); // This would be from a SupportTicket model

        return view('admin.customers.support-tickets', compact('tickets'));
    }

    public function export(Request $request)
    {
        $query = Client::with(['user', 'rentals']);

        // Apply same filters as index
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('cin', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->get();

        $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Nom', 'CIN', 'Email', 'Téléphone', 'Adresse', 
                'Date de naissance', 'Date d\'inscription', 'Total réservations', 
                'Réservations actives', 'Montant total dépensé'
            ]);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->cin,
                    $customer->user->email,
                    $customer->phone,
                    $customer->address,
                    $customer->birthday ? $customer->birthday->format('Y-m-d') : '',
                    $customer->created_at->format('Y-m-d H:i:s'),
                    $customer->rentals()->count(),
                    $customer->rentals()->where('status', 'active')->count(),
                    $customer->rentals()->whereIn('status', ['active', 'completed'])->sum('total_price')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getFavoriteCarBrand(Client $customer)
    {
        $brandCounts = $customer->rentals()
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->select('cars.brand', DB::raw('count(*) as count'))
            ->groupBy('cars.brand')
            ->orderBy('count', 'desc')
            ->first();

        return $brandCounts ? $brandCounts->brand : 'N/A';
    }
}
