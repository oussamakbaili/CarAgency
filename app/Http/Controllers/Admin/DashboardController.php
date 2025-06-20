<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingAgencies = Agency::where('status', 'pending')->count();
        $activeAgencies = Agency::where('status', 'approved')->count();
        $totalRentals = Rental::count();

        // Get recent activity
        $recentActivity = Agency::latest()
            ->take(10)
            ->get()
            ->map(function ($agency) {
                return (object)[
                    'created_at' => $agency->created_at,
                    'agency_name' => $agency->agency_name,
                    'action' => $this->getActionText($agency),
                    'status' => $agency->status
                ];
            });

        return view('admin.dashboard', compact(
            'pendingAgencies',
            'activeAgencies',
            'totalRentals',
            'recentActivity'
        ));
    }

    private function getActionText($agency)
    {
        switch ($agency->status) {
            case 'pending':
                return 'Nouvelle demande d\'inscription';
            case 'approved':
                return 'Agence approuvée';
            case 'rejected':
                return 'Agence rejetée';
            default:
                return 'Action inconnue';
        }
    }
} 