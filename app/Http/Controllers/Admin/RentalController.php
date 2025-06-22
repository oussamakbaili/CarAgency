<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['client.user', 'car.agency'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.rentals.index', compact('rentals'));
    }
}
