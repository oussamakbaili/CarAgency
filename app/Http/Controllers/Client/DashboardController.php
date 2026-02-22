<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Client;
use App\Models\Avis;
use App\Models\SupportTicket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        // If no client record exists, create one
        if (!$client) {
            $client = Client::create([
                'user_id' => $user->id,
                'cin' => 'TEMP_' . $user->id,
                'birthday' => '1990-01-01',
                'phone' => $user->phone ?? '0000000000',
                'address' => 'Address not provided',
                'city' => 'City not provided',
                'postal_code' => '00000',
            ]);
        }

        // Get comprehensive dashboard data
        $dashboardData = $this->getDashboardData($user, $client);

        return view('client.dashboard', $dashboardData);
    }

    private function getDashboardData($user, $client)
    {
        // Basic Statistics
        $statistics = $this->getStatistics($user);
        
        // Recent Activity
        $recentActivity = $this->getRecentActivity($user);
        
        // Analytics Data
        $analytics = $this->getAnalytics($user);
        
        // Quick Actions Data
        $quickActions = $this->getQuickActionsData($user);
        
        // Notifications
        $notifications = $this->getNotifications($user);
        
        // Upcoming Rentals
        $upcomingRentals = $this->getUpcomingRentals($user);
        
        // Favorite Cars
        $favoriteCars = $this->getFavoriteCars($user);
        
        // Recent Reviews
        $recentReviews = $this->getRecentReviews($user);
        
        // Support Tickets
        $supportTickets = $this->getSupportTickets($user);

        return array_merge($statistics, [
            'recentActivity' => $recentActivity,
            'analytics' => $analytics,
            'quickActions' => $quickActions,
            'notifications' => $notifications,
            'upcomingRentals' => $upcomingRentals,
            'favoriteCars' => $favoriteCars,
            'recentReviews' => $recentReviews,
            'supportTickets' => $supportTickets,
        ]);
    }

    private function getStatistics($user)
    {
        // Rental Statistics
        $totalRentals = Rental::where('user_id', $user->id)->count();
        
        $activeRentals = Rental::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->count();
        
        $pendingRentals = Rental::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $completedRentals = Rental::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $cancelledRentals = Rental::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->count();

        // Financial Statistics
        $totalSpent = Rental::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('total_price');

        $thisMonthSpent = Rental::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $averageRentalValue = $totalRentals > 0 ? $totalSpent / $totalRentals : 0;

        // Other Statistics
        $totalReviews = Avis::where('client_id', $user->id)->count();
        $averageRating = Avis::where('client_id', $user->id)->avg('rating') ?? 0;
        $supportTicketsCount = SupportTicket::where('client_id', $user->id)->count();
        $openSupportTickets = SupportTicket::where('client_id', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        // Available cars
        $availableCars = Car::availableFromApprovedAgencies()->count();

        return [
            'totalRentals' => $totalRentals,
            'activeRentals' => $activeRentals,
            'pendingRentals' => $pendingRentals,
            'completedRentals' => $completedRentals,
            'cancelledRentals' => $cancelledRentals,
            'totalSpent' => $totalSpent,
            'thisMonthSpent' => $thisMonthSpent,
            'averageRentalValue' => $averageRentalValue,
            'totalReviews' => $totalReviews,
            'averageRating' => round($averageRating, 1),
            'supportTicketsCount' => $supportTicketsCount,
            'openSupportTickets' => $openSupportTickets,
            'availableCars' => $availableCars,
        ];
    }

    private function getRecentActivity($user)
    {
        $activities = collect();

        // Recent Rentals
        $recentRentals = Rental::where('user_id', $user->id)
            ->with(['car', 'agency.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentRentals as $rental) {
            $activities->push([
                'type' => 'rental',
                'title' => 'Nouvelle location',
                'description' => "Location de {$rental->car->brand} {$rental->car->model}",
                'status' => $rental->status,
                'date' => $rental->created_at,
                'icon' => 'car',
                'color' => $this->getStatusColor($rental->status),
            ]);
        }

        // Recent Reviews
        $recentReviews = Avis::where('client_id', $user->id)
            ->with(['rental.car'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recentReviews as $review) {
            $activities->push([
                'type' => 'review',
                'title' => 'Avis publié',
                'description' => "Avis pour {$review->rental->car->brand} {$review->rental->car->model}",
                'rating' => $review->rating,
                'date' => $review->created_at,
                'icon' => 'star',
                'color' => 'yellow',
            ]);
        }

        // Recent Support Tickets
        $recentTickets = SupportTicket::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($recentTickets as $ticket) {
            $activities->push([
                'type' => 'support',
                'title' => 'Ticket de support',
                'description' => $ticket->subject,
                'status' => $ticket->status,
                'date' => $ticket->created_at,
                'icon' => 'support',
                'color' => $this->getTicketStatusColor($ticket->status),
            ]);
        }

        return $activities->sortByDesc('date')->take(10);
    }

    private function getAnalytics($user)
    {
        // Monthly spending for the last 6 months
        $monthlySpending = Rental::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Rental status distribution
        $rentalStatusDistribution = Rental::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Top car brands
        $topCarBrands = Rental::where('user_id', $user->id)
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->select('cars.brand', DB::raw('count(*) as count'))
            ->groupBy('cars.brand')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Average rental duration
        $averageDuration = Rental::where('user_id', $user->id)
            ->whereIn('status', ['completed'])
            ->selectRaw('AVG(DATEDIFF(end_date, start_date)) as avg_days')
            ->value('avg_days') ?? 0;

        return [
            'monthlySpending' => $monthlySpending,
            'rentalStatusDistribution' => $rentalStatusDistribution,
            'topCarBrands' => $topCarBrands,
            'averageDuration' => round($averageDuration, 1),
        ];
    }

    private function getQuickActionsData($user)
    {
        $client = $user->client;
        
        return [
            'profileComplete' => $this->isProfileComplete($client),
            'hasActiveRentals' => Rental::where('user_id', $user->id)
                ->where('status', 'approved')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->exists(),
            'hasPendingRentals' => Rental::where('user_id', $user->id)
                ->where('status', 'pending')
                ->exists(),
            'hasOpenSupportTickets' => SupportTicket::where('client_id', $user->id)
                ->whereIn('status', ['open', 'in_progress'])
                ->exists(),
        ];
    }

    private function getNotifications($user)
    {
        $notifications = collect();

        // Upcoming rental reminders
        $upcomingRentals = Rental::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '>', Carbon::now())
            ->where('start_date', '<=', Carbon::now()->addDays(3))
            ->get();

        foreach ($upcomingRentals as $rental) {
            $notifications->push([
                'type' => 'rental_reminder',
                'title' => 'Rappel de location',
                'message' => "Votre location de {$rental->car->brand} {$rental->car->model} commence bientôt",
                'date' => $rental->start_date,
                'priority' => 'high',
                'action_url' => route('client.rentals.show', $rental->id),
            ]);
        }

        // Rental status updates
        $recentStatusUpdates = Rental::where('user_id', $user->id)
            ->where('updated_at', '>', Carbon::now()->subDays(7))
            ->whereColumn('updated_at', '>', 'created_at')
            ->get();

        foreach ($recentStatusUpdates as $rental) {
            $notifications->push([
                'type' => 'status_update',
                'title' => 'Mise à jour de location',
                'message' => "Votre location #{$rental->id} est maintenant {$rental->status}",
                'date' => $rental->updated_at,
                'priority' => 'medium',
                'action_url' => route('client.rentals.show', $rental->id),
            ]);
        }

        return $notifications->sortByDesc('date')->take(5);
    }

    private function getUpcomingRentals($user)
    {
        return Rental::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '>', Carbon::now())
            ->with(['car', 'agency.user'])
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();
    }

    private function getFavoriteCars($user)
    {
        // Get most rented car brands/types
        $favoriteBrands = Rental::where('user_id', $user->id)
            ->join('cars', 'rentals.car_id', '=', 'cars.id')
            ->select('cars.brand', DB::raw('count(*) as rental_count'))
            ->groupBy('cars.brand')
            ->orderBy('rental_count', 'desc')
            ->take(3)
            ->get();

        // Get available cars from favorite brands
        $favoriteBrandNames = $favoriteBrands->pluck('brand')->toArray();
        
        return Car::availableFromApprovedAgencies()
            ->whereIn('brand', $favoriteBrandNames)
            ->with(['agency.user'])
            ->take(6)
            ->get();
    }

    private function getRecentReviews($user)
    {
        return Avis::where('client_id', $user->id)
            ->with(['rental.car'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    private function getSupportTickets($user)
    {
        return SupportTicket::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    private function isProfileComplete($client)
    {
        $requiredFields = ['cin', 'phone', 'address', 'city', 'postal_code', 'date_of_birth'];
        
        foreach ($requiredFields as $field) {
            if (empty($client->$field) || $client->$field === 'Address not provided' || $client->$field === 'City not provided') {
                return false;
            }
        }
        
        return true;
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'rejected' => 'red',
            'completed' => 'blue',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    private function getTicketStatusColor($status)
    {
        return match($status) {
            'open' => 'red',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray'
        };
    }
}