<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\Rental;
use App\Models\Car;
use Carbon\Carbon;

class MarketingController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        
        // Marketing overview
        $overview = [
            'total_campaigns' => 0, // Placeholder
            'active_campaigns' => 0, // Placeholder
            'email_subscribers' => 0, // Placeholder
            'social_followers' => 0, // Placeholder
        ];
        
        return view('agence.marketing.index', compact('overview'));
    }
    
    public function campaigns()
    {
        $agency = auth()->user()->agency;
        
        // Promotional campaigns
        $campaigns = [
            'active_campaigns' => [], // Placeholder
            'scheduled_campaigns' => [], // Placeholder
            'completed_campaigns' => [], // Placeholder
        ];
        
        return view('agence.marketing.campaigns', compact('campaigns'));
    }
    
    public function communications()
    {
        $agency = auth()->user()->agency;
        
        // Customer communications
        $communications = [
            'email_templates' => [], // Placeholder
            'sms_templates' => [], // Placeholder
            'notification_settings' => [], // Placeholder
        ];
        
        return view('agence.marketing.communications', compact('communications'));
    }
    
    public function referrals()
    {
        $agency = auth()->user()->agency;
        
        // Referral program
        $referrals = [
            'referral_code' => strtoupper(substr($agency->agency_name, 0, 3) . $agency->id),
            'total_referrals' => 0, // Placeholder
            'successful_referrals' => 0, // Placeholder
            'referral_earnings' => 0, // Placeholder
        ];
        
        return view('agence.marketing.referrals', compact('referrals'));
    }
    
    public function createCampaign(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,social,offline',
            'target_audience' => 'required|in:all_customers,new_customers,repeat_customers',
            'message' => 'required|string|max:1000',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        // Create campaign (would need campaigns table)
        
        return redirect()->back()->with('success', 'Campagne créée avec succès');
    }
    
    public function sendCommunication(Request $request)
    {
        $request->validate([
            'type' => 'required|in:email,sms',
            'recipients' => 'required|in:all_customers,new_customers,repeat_customers',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        
        // Send communication (would integrate with email/SMS service)
        
        return redirect()->back()->with('success', 'Communication envoyée');
    }
    
    public function generateReferralCode()
    {
        $agency = auth()->user()->agency;
        
        // Generate new referral code
        $newCode = strtoupper(substr($agency->agency_name, 0, 3) . $agency->id . rand(100, 999));
        
        return response()->json(['referral_code' => $newCode]);
    }
}
