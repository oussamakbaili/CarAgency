<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Rental;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Build query for customers
        $query = Client::whereHas('rentals', function($q) use ($agency) {
            $q->where('rentals.agency_id', $agency->id);
        })->with(['rentals' => function($q) use ($agency) {
            $q->where('rentals.agency_id', $agency->id);
        }, 'user']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('phone', 'like', "%{$search}%");
        }
        
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->where('rentals.status', 'active');
                    });
                    break;
                case 'loyal':
                    $query->withCount(['rentals' => function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id);
                    }])->having('rentals_count', '>', 1);
                    break;
                case 'new':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->whereMonth('rentals.created_at', now()->month)
                          ->whereYear('rentals.created_at', now()->year);
                    });
                    break;
            }
        }
        
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->whereDate('rentals.created_at', today());
                    });
                    break;
                case 'week':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->whereBetween('rentals.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    });
                    break;
                case 'month':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->whereMonth('rentals.created_at', now()->month)
                          ->whereYear('rentals.created_at', now()->year);
                    });
                    break;
                case 'year':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->whereYear('rentals.created_at', now()->year);
                    });
                    break;
            }
        }
        
        // Handle CSV export
        if ($request->has('export')) {
            $allCustomers = $query->get();
            return $this->exportCustomers($allCustomers);
        }
        
        $customers = $query->paginate(15);
        
        // Customer statistics using separate queries to avoid complex joins
        $totalCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id);
        })->count();
        
        $repeatCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id);
        })->withCount(['rentals' => function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id);
        }])->get()->where('rentals_count', '>', 1)->count();
        
        $newCustomersThisMonth = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id)
                  ->whereMonth('rentals.created_at', now()->month)
                  ->whereYear('rentals.created_at', now()->year);
        })->count();
        
        // Calculate additional statistics
        $totalRevenue = Rental::where('rentals.agency_id', $agency->id)
            ->where('status', 'completed')
            ->sum('total_price');
            
        $averageRentalValue = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
        
        // Calculate growth percentages (comparing with previous month)
        $previousMonthCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id)
                  ->whereMonth('rentals.created_at', now()->subMonth()->month)
                  ->whereYear('rentals.created_at', now()->subMonth()->year);
        })->count();
        
        $customerGrowth = $previousMonthCustomers > 0 ? 
            round((($newCustomersThisMonth - $previousMonthCustomers) / $previousMonthCustomers) * 100, 1) : 0;
        
        $stats = [
            'total_customers' => $totalCustomers,
            'repeat_customers' => $repeatCustomers,
            'new_customers_this_month' => $newCustomersThisMonth,
            'average_satisfaction' => 4.8, // This would come from reviews table in a real implementation
            'total_revenue' => $totalRevenue,
            'average_rental_value' => $averageRentalValue,
            'customer_growth' => $customerGrowth,
        ];
        
        return view('agence.customers.index', compact('customers', 'stats'));
    }
    
    public function profiles(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Build query for customers
        $query = Client::whereHas('rentals', function($q) use ($agency) {
            $q->where('rentals.agency_id', $agency->id);
        })->with(['rentals.car', 'user']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('phone', 'like', "%{$search}%");
        }
        
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->where('rentals.status', 'active');
                    });
                    break;
                case 'loyal':
                    $query->withCount(['rentals' => function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id);
                    }])->having('rentals_count', '>', 1);
                    break;
                case 'new':
                    $query->whereHas('rentals', function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id)
                          ->whereMonth('rentals.created_at', now()->month)
                          ->whereYear('rentals.created_at', now()->year);
                    });
                    break;
            }
        }
        
        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name':
                    $query->join('users', 'clients.user_id', '=', 'users.id')
                          ->orderBy('users.name');
                    break;
                case 'rentals':
                    $query->withCount(['rentals' => function($q) use ($agency) {
                        $q->where('rentals.agency_id', $agency->id);
                    }])->orderBy('rentals_count', 'desc');
                    break;
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $customers = $query->paginate(15);
        
        // Calculate statistics for all customers (not just paginated ones)
        $allCustomers = Client::whereHas('rentals', function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id);
        })->withCount(['rentals' => function($query) use ($agency) {
            $query->where('rentals.agency_id', $agency->id);
        }])->get();
        
        $stats = [
            'total_customers' => $allCustomers->count(),
            'active_customers' => $allCustomers->where('rentals_count', '>', 0)->count(),
            'loyal_customers' => $allCustomers->where('rentals_count', '>', 1)->count(),
            'total_rentals' => $allCustomers->sum('rentals_count'),
        ];
        
        return view('agence.customers.profiles', compact('customers', 'stats'));
    }
    
    public function reviews(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Build query for reviews
        $query = \App\Models\Avis::where('agency_id', $agency->id)
            ->where('is_public', true)
            ->with(['client.user', 'rental.car']);
        
        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('client.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'rating_high':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'rating_low':
                    $query->orderBy('rating', 'asc');
                    break;
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $reviews = $query->paginate(10);
        
        // Calculate statistics
        $totalReviews = \App\Models\Avis::where('agency_id', $agency->id)->where('is_public', true)->count();
        $averageRating = \App\Models\Avis::where('agency_id', $agency->id)->where('is_public', true)->avg('rating');
        $fiveStarReviews = \App\Models\Avis::where('agency_id', $agency->id)->where('rating', 5)->count();
        $fourStarReviews = \App\Models\Avis::where('agency_id', $agency->id)->where('rating', 4)->count();
        
        $stats = [
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 1),
            'five_star_reviews' => $fiveStarReviews,
            'four_star_reviews' => $fourStarReviews,
        ];
        
        return view('agence.customers.reviews', compact('reviews', 'stats'));
    }
    
    public function getReviewData($id)
    {
        $agency = auth()->user()->agency;
        
        $review = \App\Models\Avis::where('id', $id)
            ->where('agency_id', $agency->id)
            ->with(['client.user', 'rental.car'])
            ->first();
            
        if (!$review) {
            return response()->json(['error' => 'Review not found'], 404);
        }
        
        return response()->json([
            'id' => $review->id,
            'client_name' => $review->client->user->name ?? 'Client anonyme',
            'title' => $review->title,
            'comment' => $review->comment,
            'rating' => $review->rating,
            'vehicle' => $review->rental->car ? $review->rental->car->brand . ' ' . $review->rental->car->model : 'N/A'
        ]);
    }
    
    public function replyToReview(Request $request, $id)
    {
        $agency = auth()->user()->agency;
        
        $review = \App\Models\Avis::where('id', $id)
            ->where('agency_id', $agency->id)
            ->first();
            
        if (!$review) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        }
        
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        // For now, we'll just log the reply or store it in a simple way
        // In a real application, you might want to create a replies table
        \Log::info('Agency reply to review', [
            'review_id' => $review->id,
            'agency_id' => $agency->id,
            'message' => $request->message,
            'timestamp' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Réponse enregistrée avec succès'
        ]);
    }
    
    public function support(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Build query for support tickets
        $query = \App\Models\SupportTicket::where('agency_id', $agency->id)
            ->with(['client.user', 'rental.car']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhereHas('client.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'priority_high':
                    $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')");
                    break;
                case 'priority_low':
                    $query->orderByRaw("FIELD(priority, 'low', 'medium', 'high', 'urgent')");
                    break;
                case 'recent':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'status':
                    $query->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'closed')");
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $tickets = $query->paginate(15);
        
        // Calculate statistics
        $totalTickets = \App\Models\SupportTicket::where('agency_id', $agency->id)->count();
        $openTickets = \App\Models\SupportTicket::where('agency_id', $agency->id)->where('status', 'open')->count();
        $inProgressTickets = \App\Models\SupportTicket::where('agency_id', $agency->id)->where('status', 'in_progress')->count();
        $resolvedTickets = \App\Models\SupportTicket::where('agency_id', $agency->id)->where('status', 'resolved')->count();
        $urgentTickets = \App\Models\SupportTicket::where('agency_id', $agency->id)->where('priority', 'urgent')->count();
        
        $stats = [
            'total_tickets' => $totalTickets,
            'open_tickets' => $openTickets,
            'in_progress_tickets' => $inProgressTickets,
            'resolved_tickets' => $resolvedTickets,
            'urgent_tickets' => $urgentTickets,
        ];
        
        return view('agence.customers.support', compact('tickets', 'stats'));
    }
    
    public function getTicketDetails($id)
    {
        $agency = auth()->user()->agency;
        
        $ticket = \App\Models\SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->with(['client.user', 'rental.car'])
            ->first();
            
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }
        
        return response()->json([
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'status' => $ticket->status,
            'priority' => $ticket->priority,
            'category' => $ticket->category,
            'status_color' => $ticket->status_color,
            'priority_color' => $ticket->priority_color,
            'category_label' => $ticket->category_label,
            'client_name' => $ticket->client->user->name ?? 'Client anonyme',
            'ticket_number' => $ticket->ticket_number,
            'created_at' => $ticket->created_at->format('d/m/Y H:i'),
            'vehicle' => $ticket->rental && $ticket->rental->car ? 
                $ticket->rental->car->brand . ' ' . $ticket->rental->car->model : null
        ]);
    }
    
    public function replyToTicket(Request $request, $id)
    {
        $agency = auth()->user()->agency;
        
        $ticket = \App\Models\SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->first();
            
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
        }
        
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        // For now, we'll just log the reply
        // In a real application, you might want to create a ticket_replies table
        \Log::info('Agency reply to support ticket', [
            'ticket_id' => $ticket->id,
            'agency_id' => $agency->id,
            'message' => $request->message,
            'timestamp' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Réponse enregistrée avec succès'
        ]);
    }
    
    public function updateTicketStatus(Request $request, $id)
    {
        $agency = auth()->user()->agency;
        
        $ticket = \App\Models\SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->first();
            
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found'], 404);
        }
        
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);
        
        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority,
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? now() : null,
            'closed_at' => $request->status === 'closed' ? now() : null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès'
        ]);
    }
    
    public function exportCustomers($customers)
    {
        $filename = 'clients_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'Téléphone',
                'CIN',
                'Adresse',
                'Date de naissance',
                'Nombre de réservations',
                'Total dépensé',
                'Dernière réservation',
                'Statut'
            ]);
            
            // Add customer data
            foreach ($customers as $customer) {
                $lastRental = $customer->rentals->sortByDesc('created_at')->first();
                $totalSpent = $customer->rentals->sum('total_price');
                $rentalCount = $customer->rentals->count();
                
                fputcsv($file, [
                    $customer->id,
                    $customer->user->name ?? 'N/A',
                    $customer->user->email ?? 'N/A',
                    $customer->phone ?? 'N/A',
                    $customer->cin ?? 'N/A',
                    $customer->address ?? 'N/A',
                    $customer->birthday ? $customer->birthday->format('d/m/Y') : 'N/A',
                    $rentalCount,
                    number_format($totalSpent, 2),
                    $lastRental ? $lastRental->created_at->format('d/m/Y') : 'Jamais',
                    $rentalCount > 1 ? 'Fidèle' : ($rentalCount > 0 ? 'Actif' : 'Inactif')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
}
