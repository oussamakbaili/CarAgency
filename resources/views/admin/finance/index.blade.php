@extends('layouts.admin')

@section('header', 'Gestion Financière')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Revenus Totaux -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenus Totaux</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format(\App\Models\Rental::where('status', 'completed')->sum('total_price'), 0) }} MAD</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenus Mensuels -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenus Mensuels</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format(\App\Models\Rental::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('total_price'), 0) }} MAD</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commissions -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Commissions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format(\App\Models\Rental::where('status', 'completed')->sum('total_price') * 0.15, 0) }} MAD</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paiements en attente -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Paiements en attente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Rental::where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion Financière -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Gestion Financière</h3>
                <p class="text-sm text-gray-500">Finances et commissions</p>
            </div>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('admin.finance.dashboard') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Tableau financier</p>
                        <p class="text-xs text-gray-500">Vue d'ensemble</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.finance.commissions') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Commissions</p>
                        <p class="text-xs text-gray-500">Gérer les agences</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.finance.payments') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Paiements</p>
                        <p class="text-xs text-gray-500">Suivi des paiements</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('admin.finance.reports') }}" class="flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-gray-400 group-hover:bg-orange-600 transition-colors"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Rapports</p>
                        <p class="text-xs text-gray-500">Analyses financières</p>
                    </div>
                </div>
                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

