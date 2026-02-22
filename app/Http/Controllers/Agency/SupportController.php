<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('agency_id', auth()->user()->agency->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('agence.support.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string|max:2000',
        ]);

        SupportTicket::create([
            'agency_id' => auth()->user()->agency->id,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
            'message' => $request->message,
            'description' => $request->message, // Use message as description too
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
            'category' => 'general',
        ]);

        return redirect()->back()->with('success', 'Ticket de support créé avec succès. Notre équipe vous répondra dans les plus brefs délais.');
    }

    public function show($id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('agency_id', auth()->user()->agency->id)
            ->firstOrFail();
            
        return view('agence.support.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('agency_id', auth()->user()->agency->id)
            ->firstOrFail();

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Add reply to ticket
        $replies = $ticket->replies ?? [];
        $replies[] = [
            'user_id' => auth()->id(),
            'user_type' => 'agency',
            'message' => $request->message,
            'created_at' => now()->toISOString(),
        ];

        $ticket->update([
            'replies' => $replies,
            'status' => 'open', // Reopen if closed
        ]);

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }

    public function contact()
    {
        return view('agence.support.contact');
    }

    public function training()
    {
        return view('agence.support.training');
    }
}