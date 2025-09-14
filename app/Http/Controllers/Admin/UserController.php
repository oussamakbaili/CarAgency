<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function admins()
    {
        $admins = User::where('role', 'admin')->paginate(15);
        return view('admin.users.admins', compact('admins'));
    }

    public function roles()
    {
        $roles = [
            'admin' => 'Administrateur',
            'agence' => 'Agence',
            'client' => 'Client',
        ];

        $permissions = [
            'manage_agencies' => 'Gérer les agences',
            'manage_customers' => 'Gérer les clients',
            'manage_vehicles' => 'Gérer les véhicules',
            'manage_bookings' => 'Gérer les réservations',
            'view_reports' => 'Voir les rapports',
            'manage_finance' => 'Gérer les finances',
            'system_settings' => 'Paramètres système',
        ];

        return view('admin.users.roles', compact('roles', 'permissions'));
    }

    public function activityLogs()
    {
        // This would show admin activity logs
        $logs = collect(); // Placeholder
        return view('admin.users.activity-logs', compact('logs'));
    }

    public function security()
    {
        $securitySettings = [
            'password_min_length' => 8,
            'password_require_special' => true,
            'two_factor_enabled' => false,
            'session_timeout' => 120, // minutes
            'max_login_attempts' => 5,
            'lockout_duration' => 15, // minutes
        ];

        return view('admin.users.security', compact('securitySettings'));
    }
}
