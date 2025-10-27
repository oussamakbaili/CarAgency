<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class AgencyNotificationService
{
    /**
     * Create agency registration notification for admin
     */
    public static function notifyAdminAgencyRegistration($agency)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createAgencyNotification(
            $admin->id,
            $agency,
            'registration'
        );
    }

    /**
     * Create agency approved notification for admin
     */
    public static function notifyAdminAgencyApproved($agency)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createAgencyNotification(
            $admin->id,
            $agency,
            'approved'
        );
    }

    /**
     * Create agency rejected notification for admin
     */
    public static function notifyAdminAgencyRejected($agency)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createAgencyNotification(
            $admin->id,
            $agency,
            'rejected'
        );
    }

    /**
     * Create agency suspended notification for admin
     */
    public static function notifyAdminAgencySuspended($agency)
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        return Notification::createAgencyNotification(
            $admin->id,
            $agency,
            'suspended'
        );
    }

    /**
     * Create agency approved notification for agency
     */
    public static function notifyAgencyApproved($agency)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'high',
            'type' => 'agency_approved',
            'title' => 'Agence approuvée',
            'message' => "Félicitations ! Votre agence {$agency->nom} a été approuvée. Vous pouvez maintenant utiliser toutes les fonctionnalités de la plateforme.",
            'icon' => 'check',
            'icon_color' => 'green',
            'action_url' => route('agence.dashboard'),
            'related_id' => $agency->id,
            'data' => [
                'agency_id' => $agency->id,
                'agency_name' => $agency->nom,
                'agency_email' => $agency->email,
                'status' => $agency->status,
                'approval_date' => now(),
            ],
        ]);
    }

    /**
     * Create agency rejected notification for agency
     */
    public static function notifyAgencyRejected($agency, $reason = null)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'high',
            'type' => 'agency_rejected',
            'title' => 'Demande d\'agence rejetée',
            'message' => "Votre demande d'agence a été rejetée." . ($reason ? " Raison: {$reason}" : ""),
            'icon' => 'alert',
            'icon_color' => 'red',
            'action_url' => route('agence.profile'),
            'related_id' => $agency->id,
            'data' => [
                'agency_id' => $agency->id,
                'agency_name' => $agency->nom,
                'agency_email' => $agency->email,
                'status' => $agency->status,
                'rejection_reason' => $reason,
                'rejection_date' => now(),
            ],
        ]);
    }

    /**
     * Create agency suspended notification for agency
     */
    public static function notifyAgencySuspended($agency, $reason = null)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'high',
            'type' => 'agency_suspended',
            'title' => 'Agence suspendue',
            'message' => "Votre agence a été suspendue." . ($reason ? " Raison: {$reason}" : ""),
            'icon' => 'alert',
            'icon_color' => 'orange',
            'action_url' => route('agence.profile'),
            'related_id' => $agency->id,
            'data' => [
                'agency_id' => $agency->id,
                'agency_name' => $agency->nom,
                'agency_email' => $agency->email,
                'status' => $agency->status,
                'suspension_reason' => $reason,
                'suspension_date' => now(),
            ],
        ]);
    }

    /**
     * Create agency reactivated notification for agency
     */
    public static function notifyAgencyReactivated($agency)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'high',
            'type' => 'agency_reactivated',
            'title' => 'Agence réactivée',
            'message' => "Votre agence {$agency->nom} a été réactivée. Vous pouvez reprendre vos activités normalement.",
            'icon' => 'check',
            'icon_color' => 'green',
            'action_url' => route('agence.dashboard'),
            'related_id' => $agency->id,
            'data' => [
                'agency_id' => $agency->id,
                'agency_name' => $agency->nom,
                'agency_email' => $agency->email,
                'status' => $agency->status,
                'reactivation_date' => now(),
            ],
        ]);
    }

    /**
     * Create profile update notification for agency
     */
    public static function notifyAgencyProfileUpdated($agency)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'low',
            'type' => 'profile_updated',
            'title' => 'Profil mis à jour',
            'message' => "Votre profil d'agence a été mis à jour avec succès.",
            'icon' => 'user',
            'icon_color' => 'blue',
            'action_url' => route('agence.profile'),
            'related_id' => $agency->id,
            'data' => [
                'agency_id' => $agency->id,
                'agency_name' => $agency->nom,
                'update_date' => now(),
            ],
        ]);
    }

    /**
     * Create car added notification for agency
     */
    public static function notifyAgencyCarAdded($agency, $car)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'low',
            'type' => 'car_added',
            'title' => 'Véhicule ajouté',
            'message' => "Le véhicule {$car->marque} {$car->modele} a été ajouté à votre flotte.",
            'icon' => 'car',
            'icon_color' => 'green',
            'action_url' => route('agence.fleet.show', $car->id),
            'related_id' => $car->id,
            'data' => [
                'agency_id' => $agency->id,
                'car_id' => $car->id,
                'car_info' => "{$car->marque} {$car->modele}",
                'car_plate' => $car->plaque,
                'addition_date' => now(),
            ],
        ]);
    }

    /**
     * Create car updated notification for agency
     */
    public static function notifyAgencyCarUpdated($agency, $car)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'low',
            'type' => 'car_updated',
            'title' => 'Véhicule modifié',
            'message' => "Les informations du véhicule {$car->marque} {$car->modele} ont été mises à jour.",
            'icon' => 'car',
            'icon_color' => 'blue',
            'action_url' => route('agence.fleet.show', $car->id),
            'related_id' => $car->id,
            'data' => [
                'agency_id' => $agency->id,
                'car_id' => $car->id,
                'car_info' => "{$car->marque} {$car->modele}",
                'car_plate' => $car->plaque,
                'update_date' => now(),
            ],
        ]);
    }

    /**
     * Create car deleted notification for agency
     */
    public static function notifyAgencyCarDeleted($agency, $carInfo)
    {
        return Notification::create([
            'agency_id' => $agency->id,
            'category' => 'agency',
            'priority' => 'medium',
            'type' => 'car_deleted',
            'title' => 'Véhicule supprimé',
            'message' => "Le véhicule {$carInfo} a été supprimé de votre flotte.",
            'icon' => 'car',
            'icon_color' => 'red',
            'action_url' => route('agence.fleet.index'),
            'data' => [
                'agency_id' => $agency->id,
                'car_info' => $carInfo,
                'deletion_date' => now(),
            ],
        ]);
    }
}
