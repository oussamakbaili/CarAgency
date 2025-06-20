<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyRequestController extends Controller
{
    public function index()
    {
        $pendingAgencies = Agency::where('status', 'pending')->get();
        return view('admin.agencies.requests', compact('pendingAgencies'));
    }

    public function approve($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->status = 'approved';
        $agency->save();

        return redirect()->back()->with('success', 'Agency approved successfully.');
    }

    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->user->delete();
        $agency->delete();

        return redirect()->back()->with('success', 'Agency deleted successfully.');
    }
}
