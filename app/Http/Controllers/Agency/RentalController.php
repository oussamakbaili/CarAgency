<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\Activity;
use App\Services\RentalService;
use App\Services\AgencyCancellationService;

class RentalController extends Controller
{
    protected $cancellationService;

    public function __construct(AgencyCancellationService $cancellationService)
    {
        $this->cancellationService = $cancellationService;
    }
    public function index(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $query = Rental::where('rentals.agency_id', $agency->id)
            ->with(['car', 'user', 'agency']);
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        
        $rentals = $query->latest()->paginate(15);
        
        // Export functionality
        if ($request->has('export')) {
            return $this->exportRentals($query->get(), $request->status);
        }

        return view('agence.bookings.index', compact('rentals'));
    }

    public function pending()
    {
        $agency = auth()->user()->agency;
        
        $pendingRentals = Rental::where('rentals.agency_id', $agency->id)
            ->where('status', 'pending')
            ->with(['car', 'user', 'agency'])
            ->latest()
            ->paginate(10);

        return view('agence.rentals.pending', compact('pendingRentals'));
    }

    public function active()
    {
        $agency = auth()->user()->agency;
        
        $activeRentals = Rental::where('rentals.agency_id', $agency->id)
            ->where('status', 'active')
            ->with(['car', 'user', 'agency'])
            ->latest()
            ->paginate(15);

        return view('agence.bookings.active', compact('activeRentals'));
    }

    public function calendar(Request $request)
    {
        $agency = auth()->user()->agency;
        
        // Debug: Check if agency exists
        if (!$agency) {
            return back()->with('error', 'Aucune agence trouvée pour cet utilisateur.');
        }
        
        // Get the requested view type, month/year, week, or day
        $view = $request->get('view', 'month');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $week = $request->get('week', now()->weekOfYear);
        $day = $request->get('day', now()->day);
        
        $currentDate = \Carbon\Carbon::create($year, $month, $day);
        
        // Calculate date ranges based on view type
        if ($view === 'week') {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $currentDate->copy()->endOfWeek();
        } elseif ($view === 'day') {
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
        } else {
            // Month view
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
        }
        
        // Get rentals for the requested period
        $rentals = Rental::where('rentals.agency_id', $agency->id)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->with(['car', 'user'])
            ->get();
        
        // Debug: Log the agency ID and rental count
        \Log::info("Calendar Debug - Agency ID: {$agency->id}, Rentals found: " . $rentals->count());
        
        // Group rentals by date
        $rentalsByDate = [];
        foreach ($rentals as $rental) {
            $rentalStartDate = \Carbon\Carbon::parse($rental->start_date);
            $rentalEndDate = \Carbon\Carbon::parse($rental->end_date);
            
            // Add rental to each day it spans within the view period
            $current = $rentalStartDate->copy();
            while ($current->lte($rentalEndDate) && $current->lte($endDate)) {
                if ($current->gte($startDate)) {
                    $dateKey = $current->format('Y-m-d');
                    if (!isset($rentalsByDate[$dateKey])) {
                        $rentalsByDate[$dateKey] = [];
                    }
                    $rentalsByDate[$dateKey][] = $rental;
                }
                $current->addDay();
            }
        }
        
        // Calculate statistics
        $totalRentals = $rentals->count();
        $activeRentals = $rentals->where('status', 'active')->count();
        $pendingRentals = $rentals->where('status', 'pending')->count();
        $completedRentals = $rentals->where('status', 'completed')->count();
        
        // Calculate revenue based on view type
        if ($view === 'day') {
            $revenue = $rentals->where('status', 'completed')->sum('total_price');
        } elseif ($view === 'week') {
            $revenue = $rentals->where('status', 'completed')->sum('total_price');
        } else {
            $revenue = $rentals->where('status', 'completed')->sum('total_price');
        }
        
        // Generate calendar data based on view type
        if ($view === 'week') {
            $calendarData = $this->generateWeekData($currentDate, $rentalsByDate);
        } elseif ($view === 'day') {
            $calendarData = $this->generateDayData($currentDate, $rentalsByDate);
        } else {
            $calendarData = $this->generateCalendarData($currentDate, $rentalsByDate);
        }
        
        return view('agence.bookings.calendar', compact(
            'rentals', 
            'rentalsByDate', 
            'currentDate', 
            'calendarData',
            'totalRentals',
            'activeRentals', 
            'pendingRentals',
            'completedRentals',
            'revenue',
            'view',
            'startDate',
            'endDate'
        ));
    }
    
    private function generateCalendarData($currentDate, $rentalsByDate)
    {
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        
        // Get the first day of the week (Monday = 1)
        $firstDayOfWeek = $startOfMonth->dayOfWeekIso;
        $daysInMonth = $startOfMonth->daysInMonth;
        
        // Calculate the number of weeks needed
        $weeks = ceil(($firstDayOfWeek - 1 + $daysInMonth) / 7);
        
        $calendar = [];
        
        // Add empty cells for days before the first day of the month
        for ($i = 1; $i < $firstDayOfWeek; $i++) {
            $calendar[] = null;
        }
        
        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $startOfMonth->copy()->day($day);
            $dateKey = $date->format('Y-m-d');
            $dayRentals = $rentalsByDate[$dateKey] ?? [];
            
            $calendar[] = [
                'day' => $day,
                'date' => $date,
                'rentals' => $dayRentals,
                'rentalCount' => count($dayRentals),
                'isToday' => $date->isToday(),
                'isCurrentMonth' => true
            ];
        }
        
        // Add empty cells to complete the last week
        $remainingCells = 7 - (count($calendar) % 7);
        if ($remainingCells < 7) {
            for ($i = 0; $i < $remainingCells; $i++) {
                $calendar[] = null;
            }
        }
        
        return $calendar;
    }
    
    private function generateWeekData($currentDate, $rentalsByDate)
    {
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $weekData = [];
        
        // Generate 7 days for the week
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');
            $dayRentals = $rentalsByDate[$dateKey] ?? [];
            
            $weekData[] = [
                'day' => $date->day,
                'date' => $date,
                'dayName' => $date->format('l'),
                'dayNameShort' => $date->format('D'),
                'rentals' => $dayRentals,
                'rentalCount' => count($dayRentals),
                'isToday' => $date->isToday(),
                'isCurrentWeek' => true
            ];
        }
        
        return $weekData;
    }
    
    private function generateDayData($currentDate, $rentalsByDate)
    {
        $dateKey = $currentDate->format('Y-m-d');
        $dayRentals = $rentalsByDate[$dateKey] ?? [];
        
        // Generate hourly slots for the day
        $dayData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourStart = $currentDate->copy()->hour($hour)->minute(0);
            $hourEnd = $currentDate->copy()->hour($hour)->minute(59);
            
            // Find rentals that are active during this hour
            $hourRentals = collect($dayRentals)->filter(function($rental) use ($hourStart, $hourEnd) {
                $rentalStart = \Carbon\Carbon::parse($rental->start_date);
                $rentalEnd = \Carbon\Carbon::parse($rental->end_date);
                
                return $rentalStart->lte($hourEnd) && $rentalEnd->gte($hourStart);
            });
            
            $dayData[] = [
                'hour' => $hour,
                'time' => $hourStart->format('H:i'),
                'rentals' => $hourRentals->values()->all(), // Keep as collection of objects
                'rentalCount' => $hourRentals->count(),
                'isCurrentHour' => $hourStart->isCurrentHour(),
                'isBusinessHours' => $hour >= 8 && $hour <= 18
            ];
        }
        
        return [
            'day' => $currentDate->day,
            'date' => $currentDate,
            'dayName' => $currentDate->format('l'),
            'dayNameShort' => $currentDate->format('D'),
            'rentals' => $dayRentals,
            'rentalCount' => count($dayRentals),
            'isToday' => $currentDate->isToday(),
            'hourlyData' => $dayData
        ];
    }

    public function history(Request $request)
    {
        $agency = auth()->user()->agency;
        
        $query = Rental::where('rentals.agency_id', $agency->id)
            ->whereIn('status', ['completed', 'cancelled', 'rejected'])
            ->with(['car', 'user', 'agency']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by period
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Filter by client name
        if ($request->filled('client')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->client . '%')
                  ->orWhere('email', 'like', '%' . $request->client . '%');
            });
        }
        
        // Handle CSV export
        if ($request->has('export')) {
            $allRentals = $query->get();
            return $this->exportRentals($allRentals, 'history');
        }
        
        $rentals = $query->latest()->paginate(15);

        return view('agence.bookings.history', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        $this->authorize('view', $rental);
        
        $rental->load(['car', 'user', 'agency']);
        
        return view('agence.bookings.show', compact('rental'));
    }

    public function approve(Rental $rental)
    {
        $this->authorize('update', $rental);

        try {
            // Check if rental belongs to agency
            if ($rental->agency_id !== auth()->user()->agency->id) {
                return back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette réservation.');
            }

            // Update rental status
            $rental->update([
                'status' => 'active',
                'approved_at' => now()
            ]);

            // Update car availability
            $rental->car->update(['available_stock' => $rental->car->available_stock - 1]);

            // Log the activity
            Activity::create([
                'agency_id' => auth()->user()->agency->id,
                'type' => 'rental_approved',
                'title' => 'Réservation approuvée',
                'description' => "La réservation #{$rental->id} a été approuvée",
                'data' => ['rental_id' => $rental->id, 'user_id' => $rental->user_id]
            ]);
            
            return back()->with('success', 'La demande de location a été approuvée avec succès.');
            
        } catch (\Exception $e) {
            \Log::error('Rental approval error: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors de l\'approbation: ' . $e->getMessage());
        }
    }

    public function reject(Rental $rental)
    {
        $this->authorize('update', $rental);

        try {
            $agency = auth()->user()->agency;
            
            // Check if rental belongs to agency
            if ($rental->agency_id !== $agency->id) {
                return back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette réservation.');
            }

            // Check if agency can reject (not suspended and under cancellation limit)
            if (!$agency->canCancelBooking()) {
                return back()->with('error', 'Vous ne pouvez pas rejeter cette réservation. Votre compte est suspendu pour trop d\'annulations.')
                    ->with('warning', $agency->getCancellationWarningMessage());
            }

            // Get warning message before processing
            $warningMessage = $agency->getCancellationWarningMessage();

            // Use the cancellation service to handle the rejection
            $result = $this->cancellationService->handleCancellation(
                $agency,
                $rental,
                'agency_rejection',
                'Rejeté par l\'agence'
            );

            if (!$result['success']) {
                return back()->with('error', $result['message'])
                    ->with('warning', $result['warning'] ?? null);
            }

            // Update rental status to rejected (the service sets it to cancelled, but we want rejected)
            $rental->update([
                'status' => 'rejected',
                'rejected_at' => now()
            ]);

            // Log the activity
            Activity::create([
                'agency_id' => $agency->id,
                'type' => 'rental_rejected',
                'title' => 'Réservation rejetée',
                'description' => "La réservation #{$rental->id} a été rejetée",
                'data' => ['rental_id' => $rental->id, 'user_id' => $rental->user_id]
            ]);
            
            $response = back()->with('success', 'La demande de location a été rejetée.');
            
            // Add warning message if present
            if ($result['warning']) {
                $response->with('warning', $result['warning']);
            }
            
            return $response;
            
        } catch (\Exception $e) {
            \Log::error('Rental rejection error: ' . $e->getMessage(), [
                'rental_id' => $rental->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur lors du rejet: ' . $e->getMessage());
        }
    }
    
    private function exportRentals($rentals, $status = null)
    {
        $filename = 'rentals_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($rentals, $status) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, ['Rapport des Réservations - ' . now()->format('Y-m-d H:i:s')]);
            if ($status) {
                fputcsv($file, ['Statut filtré: ' . ucfirst($status)]);
            }
            fputcsv($file, []);
            
            // Column headers
            fputcsv($file, [
                'ID Réservation',
                'Client',
                'Email Client',
                'Véhicule',
                'Couleur',
                'Date Début',
                'Date Fin',
                'Durée (jours)',
                'Montant Total',
                'Statut',
                'Date de Création'
            ]);
            
            // Data rows
            foreach ($rentals as $rental) {
                $duration = $rental->start_date && $rental->end_date 
                    ? \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date))
                    : 0;
                    
                fputcsv($file, [
                    '#' . str_pad($rental->id, 3, '0', STR_PAD_LEFT),
                    $rental->user->name ?? 'N/A',
                    $rental->user->email ?? 'N/A',
                    $rental->car->brand . ' ' . $rental->car->model . ' ' . $rental->car->year,
                    $rental->car->color ?? 'N/A',
                    $rental->start_date ? \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') : 'N/A',
                    $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') : 'N/A',
                    $duration,
                    number_format($rental->total_price, 0) . ' DH',
                    ucfirst($rental->status),
                    \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function invoice(Rental $rental)
    {
        $this->authorize('view', $rental);
        
        // Check if rental belongs to agency
        if ($rental->agency_id !== auth()->user()->agency->id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
        }
        
        $rental->load(['car', 'user', 'agency']);
        
        return view('agence.bookings.invoice', compact('rental'));
    }
    
    public function downloadInvoice(Rental $rental)
    {
        $this->authorize('view', $rental);
        
        // Check if rental belongs to agency
        if ($rental->agency_id !== auth()->user()->agency->id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à télécharger cette facture.');
        }
        
        $rental->load(['car', 'user', 'agency']);
        
        $filename = 'facture_' . str_pad($rental->id, 3, '0', STR_PAD_LEFT) . '_' . now()->format('Y-m-d') . '.pdf';
        
        // For now, we'll return a simple HTML view that can be printed
        // In a real application, you'd use a PDF library like DomPDF or TCPDF
        return view('agence.bookings.invoice-pdf', compact('rental'))
            ->with('filename', $filename);
    }
} 