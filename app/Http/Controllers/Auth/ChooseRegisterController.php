<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class ChooseRegisterController extends Controller
{
    public function choose()
    {
        return view('auth.choose-register');
    }
}
