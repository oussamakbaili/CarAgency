@extends('layouts.public')

@section('title', 'Politique de non remboursement')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow border border-gray-200 p-8 space-y-4">
                <h1 class="text-2xl font-bold text-gray-900">Politique de non remboursement</h1>
                <p class="text-gray-700">
                    Les réservations confirmées sont non remboursables. En validant votre réservation,
                    vous acceptez que toute annulation ou modification puisse entraîner la perte totale
                    du montant payé, sauf dispositions légales contraires.
                </p>
                <p class="text-gray-700">
                    Pour toute question, contactez notre support ou l'agence concernée avant de confirmer.
                </p>
                <a href="{{ route('public.home') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold">
                    &larr; Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
@endsection

