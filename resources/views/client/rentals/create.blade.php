@extends('layouts.client')

@section('title', 'Louer un Véhicule')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Louer un Véhicule</h1>
                    <p class="text-gray-600 mt-1">Sélectionnez vos dates et confirmez votre réservation</p>
                </div>
                <a href="{{ route('client.cars.show', $car) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Il y a eu des erreurs avec votre demande.
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Car Information -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Véhicule sélectionné</h2>
                    
                    <!-- Car Image and Details -->
                    <div class="mb-6">
                        <div class="relative h-48 bg-gray-100 rounded-lg mb-4 overflow-hidden">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-100">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                                <p class="text-gray-600">{{ $car->year }} • {{ $car->registration_number }}</p>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold text-blue-600">{{ number_format($car->price_per_day, 0) }} MAD</span>
                                <span class="text-gray-600">/jour</span>
                            </div>
                        </div>
                    </div>

                    <!-- Car Features -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Caractéristiques</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @if($car->fuel_type)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    </svg>
                                    {{ ucfirst($car->fuel_type) }}
                                </div>
                            @endif
                            @if($car->transmission)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    {{ ucfirst($car->transmission) }}
                                </div>
                            @endif
                            @if($car->seats)
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $car->seats }} places
                                </div>
                            @endif
                            @if($car->color)
                                <div class="flex items-center text-sm text-gray-600">
                                    <div class="w-4 h-4 mr-2 rounded-full bg-gray-400 border border-gray-300"></div>
                                    {{ ucfirst($car->color) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Agency Information -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Agence
                        </h4>
                        <p class="text-gray-900 font-medium">{{ $car->agency->agency_name }}</p>
                        <p class="text-sm text-gray-600">{{ $car->agency->address ?? 'Adresse non spécifiée' }}</p>
                    </div>
                </div>
            </div>

            <!-- Rental Form -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-6">Détails de la location</h2>
                    
                    <form method="POST" action="{{ route('client.rentals.store', $car) }}" id="rental-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de début
                                </label>
                                <div class="relative">
                                    <input type="text" id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}" 
                                           placeholder="Sélectionner une date" 
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" 
                                           readonly required>
                                    <button type="button" id="start-date-btn" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-500 transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date de fin
                                </label>
                                <div class="relative">
                                    <input type="text" id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}" 
                                           placeholder="Sélectionner une date" 
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" 
                                           readonly required>
                                    <button type="button" id="end-date-btn" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-500 transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('end_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Availability Calendar -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Calendrier de disponibilité</h3>
                            <div id="selection-status" class="text-sm text-gray-600 mb-4 p-3 bg-white rounded-lg border border-gray-200">
                                <span class="font-medium">Étape 1:</span> Cliquez sur "Date de début" pour commencer
                            </div>
                            <div id="availability-calendar" class="text-center text-sm">
                                <div class="col-span-7 text-center py-4 text-gray-500">
                                    <div class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Chargement du calendrier...
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-center space-x-6 text-xs">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-200 border border-green-400 rounded mr-2"></div>
                                    <span class="text-gray-600">Disponible</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-200 border border-red-400 rounded mr-2"></div>
                                    <span class="text-gray-600">Occupé</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-200 border border-blue-400 rounded mr-2"></div>
                                    <span class="text-gray-600">Sélectionné</span>
                                </div>
                            </div>
                        </div>

                        <!-- Price Calculation -->
                        <div class="bg-blue-50 rounded-lg p-6 mb-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Calcul du prix</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Prix par jour:</span>
                                    <span class="text-gray-900 font-medium">{{ number_format($car->price_per_day, 0) }} MAD</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Nombre de jours:</span>
                                    <span class="text-gray-900 font-medium" id="days-count">-</span>
                                </div>
                                <hr class="border-gray-300">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-blue-600" id="total-price">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Disclaimer Clause -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Clause de non-responsabilité
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>
                                            En confirmant cette réservation, vous acceptez que <strong>RentCar Platform</strong> 
                                            n'est pas responsable des remboursements en cas de litige entre le client 
                                            et le fournisseur. Tous les litiges doivent être résolus directement entre 
                                            les parties concernées.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Acceptance -->
                        <div class="flex items-start mb-6">
                            <div class="flex items-center h-5">
                                <input id="terms_accepted" name="terms_accepted" type="checkbox" 
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                       required>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms_accepted" class="font-medium text-gray-700">
                                    J'accepte les conditions de non-responsabilité
                                </label>
                                <p class="text-gray-500 mt-1">
                                    Je comprends que RentCar Platform n'est pas responsable des remboursements en cas de litige.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Confirmer la demande de location
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .calendar-day {
            transition: all 0.2s ease;
        }
        .calendar-day:hover:not(.unavailable):not(.past) {
            transform: scale(1.05);
        }
        .calendar-day.selected {
            box-shadow: 0 0 0 2px #3b82f6;
        }
        .date-input-active {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
        }
        .date-input-inactive {
            border-color: #d1d5db !important;
        }
        #availability-calendar {
            display: block;
            position: relative;
            z-index: 10;
            min-height: 300px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            text-align: center;
        }
        .calendar-container {
            position: relative;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const daysCountElement = document.getElementById('days-count');
            const totalPriceElement = document.getElementById('total-price');
            const pricePerDay = {{ $car->price_per_day }};
            const carId = {{ $car->id }};
            
            let unavailableDates = [];
            let selectedStartDate = null;
            let selectedEndDate = null;
            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();
            let activeInput = null;

            // Load unavailable dates
            async function loadUnavailableDates() {
                try {
                    const response = await fetch(`/client/cars/${carId}/unavailable-dates`);
                    const data = await response.json();
                    unavailableDates = data.unavailable_dates;
                    console.log('Unavailable dates loaded:', unavailableDates);
                    generateCalendar();
                } catch (error) {
                    console.error('Error loading unavailable dates:', error);
                    generateCalendar();
                }
            }

            // Generate calendar
            function generateCalendar() {
                const calendar = document.getElementById('availability-calendar');
                const today = new Date();
                
                calendar.innerHTML = '';
                
                // Add month header with navigation
                const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                const monthHeader = document.createElement('div');
                monthHeader.className = 'flex items-center justify-between mb-4';
                monthHeader.innerHTML = `
                    <button type="button" id="prev-month" class="p-2 hover:bg-gray-200 rounded-full transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h3 class="font-semibold text-gray-900">${monthNames[currentMonth]} ${currentYear}</h3>
                    <button type="button" id="next-month" class="p-2 hover:bg-gray-200 rounded-full transition duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                `;
                calendar.appendChild(monthHeader);
                
                // Create calendar grid container
                const calendarGrid = document.createElement('div');
                calendarGrid.className = 'calendar-grid';
                
                // Add day headers
                const dayHeaders = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                dayHeaders.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'text-xs font-medium text-gray-500 py-2 text-center';
                    dayHeader.textContent = day;
                    calendarGrid.appendChild(dayHeader);
                });
                
                // Get first day of month and number of days
                const firstDay = new Date(currentYear, currentMonth, 1);
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startDayOfWeek = (firstDay.getDay() + 6) % 7;
                
                // Add empty cells for days before month starts
                for (let i = 0; i < startDayOfWeek; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'h-10';
                    calendarGrid.appendChild(emptyCell);
                }
                
                // Add days of month
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(currentYear, currentMonth, day);
                    const dateString = date.toISOString().split('T')[0];
                    const isPast = date < today;
                    const isUnavailable = unavailableDates.includes(dateString);
                    const isSelected = (selectedStartDate && dateString === selectedStartDate) || 
                                     (selectedEndDate && dateString === selectedEndDate) ||
                                     (selectedStartDate && selectedEndDate && date >= new Date(selectedStartDate) && date <= new Date(selectedEndDate));
                    
                    const dayCell = document.createElement('div');
                    dayCell.className = `h-10 flex items-center justify-center text-sm rounded cursor-pointer calendar-day ${
                        isPast ? 'text-gray-300' :
                        isUnavailable ? 'bg-red-200 border border-red-400 text-red-800 cursor-not-allowed' :
                        isSelected ? 'bg-blue-200 border border-blue-400 text-blue-800' :
                        'bg-green-200 border border-green-400 text-green-800 hover:bg-green-300'
                    }`;
                    dayCell.textContent = day;
                    
                    if (!isPast && !isUnavailable) {
                        dayCell.addEventListener('click', () => selectDate(dateString));
                    }
                    
                    calendarGrid.appendChild(dayCell);
                }
                
                calendar.appendChild(calendarGrid);
                updateStatusBasedOnState();

                // Add navigation event listeners
                const prevButton = document.getElementById('prev-month');
                const nextButton = document.getElementById('next-month');
                
                if (prevButton) {
                    prevButton.replaceWith(prevButton.cloneNode(true));
                    const newPrevButton = document.getElementById('prev-month');
                    newPrevButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentMonth--;
                        if (currentMonth < 0) {
                            currentMonth = 11;
                            currentYear--;
                        }
                        generateCalendar();
                    });
                }

                if (nextButton) {
                    nextButton.replaceWith(nextButton.cloneNode(true));
                    const newNextButton = document.getElementById('next-month');
                    newNextButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentMonth++;
                        if (currentMonth > 11) {
                            currentMonth = 0;
                            currentYear++;
                        }
                        generateCalendar();
                    });
                }
            }

            // Select date
            function selectDate(dateString) {
                if (activeInput === 'start') {
                    selectedStartDate = dateString;
                    selectedEndDate = null;
                    startDateInput.value = formatDateForDisplay(dateString);
                    endDateInput.value = '';
                    
                    updateStatusMessage('Étape 2: Maintenant cliquez sur "Date de fin" pour sélectionner votre date de fin');
                    
                    activeInput = 'end';
                    endDateInput.focus();
                    generateCalendar();
                } else if (activeInput === 'end') {
                    const startDate = new Date(selectedStartDate);
                    const endDate = new Date(dateString);
                    
                    if (endDate > startDate) {
                        selectedEndDate = dateString;
                        endDateInput.value = formatDateForDisplay(dateString);
                        
                        updateStatusMessage('✅ Sélection terminée! Vérifiez vos dates ci-dessus.');
                        hideCalendar();
                    } else {
                        selectedEndDate = selectedStartDate;
                        selectedStartDate = dateString;
                        startDateInput.value = formatDateForDisplay(dateString);
                        endDateInput.value = formatDateForDisplay(selectedEndDate);
                        
                        updateStatusMessage('✅ Sélection terminée! Vérifiez vos dates ci-dessus.');
                        hideCalendar();
                    }
                }
                
                calculatePrice();
            }

            // Update status message
            function updateStatusMessage(message) {
                const statusElement = document.getElementById('selection-status');
                statusElement.innerHTML = message;
            }

            // Hide calendar
            function hideCalendar() {
                const calendar = document.getElementById('availability-calendar');
                calendar.style.display = 'none';
            }

            // Show calendar
            function showCalendar() {
                const calendar = document.getElementById('availability-calendar');
                calendar.style.display = 'block';
                
                if (selectedStartDate) {
                    startDateInput.value = formatDateForDisplay(selectedStartDate);
                }
                if (selectedEndDate) {
                    endDateInput.value = formatDateForDisplay(selectedEndDate);
                }
                
                generateCalendar();
            }

            // Update status message based on current state
            function updateStatusBasedOnState() {
                if (selectedStartDate && selectedEndDate) {
                    updateStatusMessage('✅ Sélection terminée! Vérifiez vos dates ci-dessus.');
                } else if (selectedStartDate) {
                    updateStatusMessage('Étape 2: Maintenant cliquez sur "Date de fin" pour sélectionner votre date de fin');
                } else {
                    updateStatusMessage('Étape 1: Cliquez sur "Date de début" pour commencer');
                }
            }

            // Format date for display
            function formatDateForDisplay(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            // Format date for form submission
            function formatDateForForm(dateString) {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            }

            function calculatePrice() {
                if (selectedStartDate && selectedEndDate) {
                    const startDate = new Date(selectedStartDate);
                    const endDate = new Date(selectedEndDate);
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    const totalPrice = daysDiff * pricePerDay;

                    daysCountElement.textContent = daysDiff;
                    totalPriceElement.textContent = totalPrice.toLocaleString() + ' MAD';
                } else {
                    daysCountElement.textContent = '-';
                    totalPriceElement.textContent = '-';
                }
            }

            // Input click handlers
            startDateInput.addEventListener('click', function() {
                activeInput = 'start';
                updateStatusMessage('Étape 1: Sélectionnez votre date de début dans le calendrier');
                showCalendar();
            });

            endDateInput.addEventListener('click', function() {
                activeInput = 'end';
                if (selectedStartDate) {
                    updateStatusMessage('Étape 2: Sélectionnez votre date de fin dans le calendrier');
                } else {
                    updateStatusMessage('⚠️ Veuillez d\'abord sélectionner une date de début');
                }
                showCalendar();
            });

            // Add visual feedback for active input
            startDateInput.addEventListener('focus', function() {
                activeInput = 'start';
                updateStatusMessage('Étape 1: Sélectionnez votre date de début dans le calendrier');
                showCalendar();
                this.classList.add('date-input-active');
                endDateInput.classList.remove('date-input-active');
                endDateInput.classList.add('date-input-inactive');
            });

            endDateInput.addEventListener('focus', function() {
                activeInput = 'end';
                if (selectedStartDate) {
                    updateStatusMessage('Étape 2: Sélectionnez votre date de fin dans le calendrier');
                } else {
                    updateStatusMessage('⚠️ Veuillez d\'abord sélectionner une date de début');
                }
                showCalendar();
                this.classList.add('date-input-active');
                startDateInput.classList.remove('date-input-active');
                startDateInput.classList.add('date-input-inactive');
            });

            // Button click handlers for calendar icons
            document.getElementById('start-date-btn').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                activeInput = 'start';
                updateStatusMessage('Étape 1: Sélectionnez votre date de début dans le calendrier');
                showCalendar();
                startDateInput.focus();
            });

            document.getElementById('end-date-btn').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                activeInput = 'end';
                if (selectedStartDate) {
                    updateStatusMessage('Étape 2: Sélectionnez votre date de fin dans le calendrier');
                } else {
                    updateStatusMessage('⚠️ Veuillez d\'abord sélectionner une date de début');
                }
                showCalendar();
                endDateInput.focus();
            });

            // Hide calendar when clicking outside
            document.addEventListener('click', function(e) {
                const calendar = document.getElementById('availability-calendar');
                const startInput = document.getElementById('start_date');
                const endInput = document.getElementById('end_date');
                const startBtn = document.getElementById('start-date-btn');
                const endBtn = document.getElementById('end-date-btn');
                const prevButton = document.getElementById('prev-month');
                const nextButton = document.getElementById('next-month');
                
                if (!calendar.contains(e.target) && 
                    !startInput.contains(e.target) && 
                    !endInput.contains(e.target) &&
                    !startBtn.contains(e.target) &&
                    !endBtn.contains(e.target) &&
                    e.target !== prevButton &&
                    e.target !== nextButton) {
                    hideCalendar();
                }
            });

            // Form submission - convert display dates back to ISO format
            document.getElementById('rental-form').addEventListener('submit', function(e) {
                if (selectedStartDate) {
                    startDateInput.value = formatDateForForm(selectedStartDate);
                }
                if (selectedEndDate) {
                    endDateInput.value = formatDateForForm(selectedEndDate);
                }
            });

            // Load unavailable dates and generate calendar
            loadUnavailableDates();
            generateCalendar();
        });
    </script>
@endsection