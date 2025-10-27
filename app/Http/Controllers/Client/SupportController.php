<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display support index page
     */
    public function index()
    {
        $client = auth()->user()->client;
        
        $tickets = SupportTicket::where('client_id', $client->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('client.support.index', compact('tickets'));
    }

    /**
     * Show support messages interface
     */
    public function messages()
    {
        return view('client.support.messages');
    }

    /**
     * Get tickets for client (for AJAX requests)
     */
    public function getTickets()
    {
        $client = auth()->user()->client;
        
        $tickets = SupportTicket::where('client_id', $client->id)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function($ticket) use ($client) {
                $ticket->unread_messages_count = $ticket->getUnreadMessagesCount($client);
                return $ticket;
            });

        return response()->json([
            'success' => true,
            'tickets' => $tickets,
        ]);
    }

    /**
     * Create a new support ticket
     */
    public function create()
    {
        return view('client.support.create');
    }

    /**
     * Store a new support ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string|max:2000',
            'category' => 'required|in:technical,billing,general,complaint',
        ]);

        $client = auth()->user()->client;

        $ticket = SupportTicket::create([
            'client_id' => $client->id,
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'category' => $request->category,
            'priority' => $request->priority,
            'subject' => $request->subject,
            'message' => $request->message,
            'description' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('client.support.index')
            ->with('success', 'Votre ticket de support a été envoyé avec succès ! Notre équipe vous répondra dans les plus brefs délais.');
    }

    /**
     * Show a specific ticket
     */
    public function show($id)
    {
        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $id)
            ->where('client_id', $client->id)
            ->firstOrFail();

        return view('client.support.show', compact('ticket'));
    }

    /**
     * Reply to a ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $id)
            ->where('client_id', $client->id)
            ->firstOrFail();

        // Find admin users to send message to
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return redirect()->back()->with('error', 'Aucun administrateur trouvé');
        }

        // Create the message using new system
        $ticket->sendMessage($client, $admin, $request->message);

        // Update ticket status to open if it was closed
        if ($ticket->status === 'closed') {
            $ticket->reopen();
        }

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès.');
    }

    /**
     * Mark ticket as resolved
     */
    public function markResolved($id)
    {
        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $id)
            ->where('client_id', $client->id)
            ->firstOrFail();

        $ticket->markAsResolved();

        return redirect()->back()->with('success', 'Ticket marqué comme résolu.');
    }

    /**
     * Reopen a ticket
     */
    public function reopen($id)
    {
        $client = auth()->user()->client;
        $ticket = SupportTicket::where('id', $id)
            ->where('client_id', $client->id)
            ->firstOrFail();

        $ticket->reopen();

        return redirect()->back()->with('success', 'Ticket rouvert avec succès.');
    }

    /**
     * Contact support page
     */
    public function contact()
    {
        return view('client.support.contact');
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
            'category' => 'required|in:technical,billing,general,complaint',
        ]);

        $client = auth()->user()->client;

        $ticket = SupportTicket::create([
            'client_id' => $client->id,
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'category' => $request->category,
            'priority' => $request->priority,
            'subject' => $request->subject,
            'message' => $request->message,
            'description' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('client.support.index')
            ->with('success', 'Votre message de support a été envoyé avec succès ! Notre équipe vous répondra dans les plus brefs délais.');
    }
}