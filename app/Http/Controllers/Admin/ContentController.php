<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function settings()
    {
        // Site settings management
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => 'Plateforme de location de véhicules',
            'contact_email' => 'contact@example.com',
            'contact_phone' => '+212 5XX XXX XXX',
            'address' => 'Casablanca, Maroc',
            'social_media' => [
                'facebook' => '',
                'twitter' => '',
                'instagram' => '',
                'linkedin' => '',
            ],
        ];

        return view('admin.content.settings', compact('settings'));
    }

    public function banners()
    {
        // Banner management
        $banners = collect([
            [
                'id' => 1,
                'title' => 'Banner Principal',
                'image' => '/images/banner1.jpg',
                'link' => '/',
                'status' => 'active',
                'position' => 'homepage_top',
            ],
        ]);

        return view('admin.content.banners', compact('banners'));
    }

    public function faq()
    {
        // FAQ management
        $faqs = collect([
            [
                'id' => 1,
                'question' => 'Comment réserver un véhicule ?',
                'answer' => 'Vous pouvez réserver un véhicule en créant un compte et en parcourant notre catalogue.',
                'category' => 'Réservation',
                'status' => 'active',
            ],
        ]);

        return view('admin.content.faq', compact('faqs'));
    }

    public function policies()
    {
        // Terms and policies management
        $policies = [
            'terms_of_service' => 'Contenu des conditions d\'utilisation...',
            'privacy_policy' => 'Contenu de la politique de confidentialité...',
            'cancellation_policy' => 'Contenu de la politique d\'annulation...',
            'refund_policy' => 'Contenu de la politique de remboursement...',
        ];

        return view('admin.content.policies', compact('policies'));
    }
}
