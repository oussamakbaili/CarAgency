<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Changer la langue de l'application
     */
    public function switch(Request $request, $locale)
    {
        // Valider que la langue est supportée
        $supportedLocales = ['fr', 'en'];
        
        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back()->with('error', 'Langue non supportée');
        }
        
        // Stocker la langue dans la session
        Session::put('locale', $locale);
        
        // Rediriger vers la page précédente ou la page d'accueil
        return redirect()->back();
    }
}
