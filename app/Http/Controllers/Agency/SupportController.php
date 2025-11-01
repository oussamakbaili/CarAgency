<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Rental;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display agency support page with their tickets
     */
    public function index(Request $request)
    {
        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return redirect()->route('agence.pending')
                ->with('error', 'Votre compte agence n\'est pas encore configuré.');
        }
        
        $tickets = SupportTicket::where('agency_id', $agency->id)
            ->whereNull('client_id') // S'assurer que c'est un ticket créé par l'agence, pas par un client
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('agence.support.index', compact('tickets', 'agency'));
    }

    /**
     * Show form to create new ticket
     */
    public function create()
    {
        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return redirect()->route('agence.pending')
                ->with('error', 'Votre compte agence n\'est pas encore configuré.');
        }
        
        // Get agency's recent rentals with valid start dates (only if agency is approved)
        $rentals = collect([]);
        if ($agency->status === 'approved') {
            $rentals = Rental::whereHas('car', function($q) use ($agency) {
                    $q->where('agency_id', $agency->id);
                })
                ->where('status', '!=', 'rejected')
                ->whereNotNull('start_date')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('agence.support.create', compact('rentals', 'agency'));
    }

    /**
     * Store new support ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:technical,billing,booking,general,complaint,account',
            'priority' => 'required|in:low,medium,high,urgent',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'rental_id' => 'nullable|exists:rentals,id'
        ]);

        $agency = auth()->user()->agency;

        if (!$agency) {
            return redirect()->route('agence.pending')
                ->with('error', 'Votre compte agence n\'est pas encore configuré.');
        }

        // Verify rental belongs to agency if provided (only for approved agencies)
        if ($request->rental_id && $agency->status === 'approved') {
            $rental = Rental::whereHas('car', function($q) use ($agency) {
                    $q->where('agency_id', $agency->id);
                })
                ->where('id', $request->rental_id)
                ->first();
            
            if (!$rental) {
                return redirect()->back()
                    ->with('error', 'Location invalide.')
                    ->withInput();
            }
        } else {
            // For pending agencies, ignore rental_id
            $request->merge(['rental_id' => null]);
        }

        $ticket = SupportTicket::create([
            'agency_id' => $agency->id,
            'rental_id' => $request->rental_id,
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'category' => $request->category,
            'priority' => $request->priority,
            'subject' => $request->subject,
            'message' => $request->message,
            'description' => $request->message,
            'status' => 'open',
        ]);

        // Redirect based on agency status
        $redirectRoute = $agency->status === 'approved' 
            ? route('agence.support.index') 
            : route('agence.pending');

        return redirect($redirectRoute)
            ->with('success', 'Votre ticket de support a été envoyé avec succès ! Notre équipe vous répondra dans les plus brefs délais.');
    }

    /**
     * Display ticket details
     */
    public function show($id)
    {
        $agency = auth()->user()->agency;
        
        $ticket = SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->with(['rental', 'assignedTo'])
            ->firstOrFail();
            
        return view('agence.support.show', compact('ticket'));
    }

    /**
     * Add reply to ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        $agency = auth()->user()->agency;
        
        if (!$agency) {
            return redirect()->route('agence.pending')
                ->with('error', 'Votre compte agence n\'est pas encore configuré.');
        }
        
        $ticket = SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->firstOrFail();

        $ticket->addReply(
            $request->message,
            auth()->id(),
            'agency'
        );

        // If ticket was resolved or closed, reopen it
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->reopen();
        }

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Mark ticket as resolved (agency satisfaction)
     */
    public function markResolved($id)
    {
        $agency = auth()->user()->agency;
        
        $ticket = SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->firstOrFail();

        $ticket->markAsResolved();
        
        $ticket->addReply(
            'Agence a marqué ce ticket comme résolu.',
            auth()->id(),
            'system'
        );

        return redirect()->back()->with('success', 'Ticket marqué comme résolu. Merci pour votre retour !');
    }

    /**
     * Reopen a closed ticket
     */
    public function reopen($id)
    {
        $agency = auth()->user()->agency;
        
        $ticket = SupportTicket::where('id', $id)
            ->where('agency_id', $agency->id)
            ->firstOrFail();

        $ticket->reopen();
        
        $ticket->addReply(
            'Agence a rouvert ce ticket.',
            auth()->id(),
            'system'
        );

        return redirect()->back()->with('success', 'Ticket rouvert avec succès.');
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return view('agence.support.contact');
    }

    /**
     * Training resources page
     */
    public function training()
    {
        return view('agence.support.training');
    }

    /**
     * Store contact form submission
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string|max:2000',
        ]);

        $agency = auth()->user()->agency;

        $ticket = SupportTicket::create([
            'agency_id' => $agency->id,
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'category' => 'general', // Par défaut général pour le formulaire de contact
            'priority' => $request->priority,
            'subject' => $request->subject,
            'message' => $request->message,
            'description' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('agence.support.index')
            ->with('success', 'Votre message de support a été envoyé avec succès ! Notre équipe vous répondra dans les plus brefs délais.');
    }

    /**
     * Get tickets for agency (for AJAX requests)
     */
    public function getTickets()
    {
        $agency = auth()->user()->agency;
        
        $tickets = SupportTicket::where('agency_id', $agency->id)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($ticket) {
                $ticket->unread_messages_count = $ticket->getUnreadMessagesCount($agency);
                return $ticket;
            });

        return response()->json([
            'success' => true,
            'tickets' => $tickets,
        ]);
    }
}
