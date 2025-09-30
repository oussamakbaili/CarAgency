<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Louer un Véhicule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('client.cars.show', $car) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            ← Retour
                        </a>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Car Summary -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Véhicule sélectionné</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    @if($car->image)
                                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $car->brand }} {{ $car->model }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $car->year }} • {{ $car->registration_number }}</p>
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($car->price_per_day, 2) }}€/jour</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Agency Info -->
                            <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Agence</h4>
                                <p class="text-gray-700 dark:text-gray-300">{{ $car->agency->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $car->agency->address ?? 'Adresse non spécifiée' }}</p>
                            </div>
                        </div>

                        <!-- Rental Form -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Détails de la location</h3>
                            
                            @if ($errors->any())
                                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                                Il y a eu des erreurs avec votre demande.
                                            </h3>
                                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
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

                            <form method="POST" action="{{ route('client.rentals.store', $car) }}" id="rental-form">
                                @csrf
                                
                                <div class="space-y-4">
                                    <!-- Date Selection with Enhanced Calendar -->
                                    <div>
                                        <x-input-label for="start_date" :value="__('Date de début')" />
                                        <div class="relative">
                                            <x-text-input id="start_date" class="block mt-1 w-full pr-10" type="text" name="start_date" :value="old('start_date')" placeholder="Sélectionner une date" readonly required />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="end_date" :value="__('Date de fin')" />
                                        <div class="relative">
                                            <x-text-input id="end_date" class="block mt-1 w-full pr-10" type="text" name="end_date" :value="old('end_date')" placeholder="Sélectionner une date" readonly required />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                    </div>

                                    <!-- Availability Calendar -->
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 calendar-container">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Calendrier de disponibilité</h4>
                                        <div id="selection-status" class="text-sm text-gray-600 dark:text-gray-400 mb-3">
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
                                        <div class="mt-3 flex items-center justify-center space-x-4 text-xs">
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-green-200 border border-green-400 rounded mr-1"></div>
                                                <span class="text-gray-600 dark:text-gray-400">Disponible</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-red-200 border border-red-400 rounded mr-1"></div>
                                                <span class="text-gray-600 dark:text-gray-400">Occupé</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-3 h-3 bg-blue-200 border border-blue-400 rounded mr-1"></div>
                                                <span class="text-gray-600 dark:text-gray-400">Sélectionné</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price Calculation -->
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Calcul du prix</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Prix par jour:</span>
                                                <span class="text-gray-900 dark:text-gray-100">{{ number_format($car->price_per_day, 2) }}€</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Nombre de jours:</span>
                                                <span class="text-gray-900 dark:text-gray-100" id="days-count">-</span>
                                            </div>
                                            <hr class="border-gray-300 dark:border-gray-600">
                                            <div class="flex justify-between font-semibold">
                                                <span class="text-gray-900 dark:text-gray-100">Total:</span>
                                                <span class="text-blue-600 dark:text-blue-400" id="total-price">-</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Disclaimer Clause -->
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                    Clause de non-responsabilité
                                                </h3>
                                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
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

                                    <!-- Terms Acceptance Checkbox -->
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms_accepted" name="terms_accepted" type="checkbox" 
                                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" 
                                                   required>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="terms_accepted" class="font-medium text-gray-700 dark:text-gray-300">
                                                J'accepte les conditions de non-responsabilité
                                            </label>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                Je comprends que RentCar Platform n'est pas responsable des remboursements en cas de litige.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <x-primary-button class="w-full justify-center">
                                            {{ __('Confirmer la demande de location') }}
                                        </x-primary-button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                    // Generate calendar even if unavailable dates fail to load
                    generateCalendar();
                }
            }

            // Generate calendar
            function generateCalendar() {
                const calendar = document.getElementById('availability-calendar');
                const today = new Date();
                
                console.log('Generating calendar for:', currentMonth, currentYear);
                console.log('Calendar element:', calendar);
                
                // Clear calendar
                calendar.innerHTML = '';
                
                // Add month header with navigation
                const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                const monthHeader = document.createElement('div');
                monthHeader.className = 'flex items-center justify-between mb-4';
                monthHeader.innerHTML = `
                    <button type="button" id="prev-month" class="p-2 hover:bg-gray-200 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">${monthNames[currentMonth]} ${currentYear}</h3>
                    <button type="button" id="next-month" class="p-2 hover:bg-gray-200 rounded-full">
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
                    dayHeader.className = 'text-xs font-medium text-gray-500 dark:text-gray-400 py-2 text-center';
                    dayHeader.textContent = day;
                    calendarGrid.appendChild(dayHeader);
                });
                
                // Get first day of month and number of days
                const firstDay = new Date(currentYear, currentMonth, 1);
                const lastDay = new Date(currentYear, currentMonth + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startDayOfWeek = (firstDay.getDay() + 6) % 7; // Convert Sunday=0 to Monday=0
                
                // Add empty cells for days before month starts
                for (let i = 0; i < startDayOfWeek; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'h-10';
                    calendarGrid.appendChild(emptyCell);
                }
                
                // Add days of month
                console.log('Adding days for month:', daysInMonth, 'days');
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
                        isPast ? 'text-gray-300 dark:text-gray-600' :
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
                
                // Add the grid to the calendar
                calendar.appendChild(calendarGrid);
                console.log('Calendar generated with', calendarGrid.children.length, 'grid elements');
                
                // Update status based on current selection state
                updateStatusBasedOnState();

                // Add navigation event listeners (remove existing ones first)
                const prevButton = document.getElementById('prev-month');
                const nextButton = document.getElementById('next-month');
                
                // Remove existing event listeners
                if (prevButton) {
                    prevButton.replaceWith(prevButton.cloneNode(true));
                }
                if (nextButton) {
                    nextButton.replaceWith(nextButton.cloneNode(true));
                }
                
                // Add new event listeners
                const newPrevButton = document.getElementById('prev-month');
                const newNextButton = document.getElementById('next-month');
                
                if (newPrevButton) {
                    newPrevButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        currentMonth--;
                        if (currentMonth < 0) {
                            currentMonth = 11;
                            currentYear--;
                        }
                        generateCalendar();
                    });
                }

                if (newNextButton) {
                    newNextButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
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
                    
                    // Update status message
                    updateStatusMessage('Étape 2: Maintenant cliquez sur "Date de fin" pour sélectionner votre date de fin');
                    
                    // Automatically switch to end date selection
                    activeInput = 'end';
                    endDateInput.focus();
                    generateCalendar();
                } else if (activeInput === 'end') {
                    const startDate = new Date(selectedStartDate);
                    const endDate = new Date(dateString);
                    
                    if (endDate > startDate) {
                        selectedEndDate = dateString;
                        endDateInput.value = formatDateForDisplay(dateString);
                        
                        // Update status message
                        updateStatusMessage('✅ Sélection terminée! Vérifiez vos dates ci-dessus.');
                        
                        // Hide calendar after both dates are selected
                        hideCalendar();
                    } else {
                        // Swap dates if end is before start
                        selectedEndDate = selectedStartDate;
                        selectedStartDate = dateString;
                        startDateInput.value = formatDateForDisplay(dateString);
                        endDateInput.value = formatDateForDisplay(selectedEndDate);
                        
                        // Update status message
                        updateStatusMessage('✅ Sélection terminée! Vérifiez vos dates ci-dessus.');
                        
                        // Hide calendar after both dates are selected
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
                
                // Restore input values if dates are already selected
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
                    totalPriceElement.textContent = totalPrice.toFixed(2) + '€';
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

            // Hide calendar when clicking outside
            document.addEventListener('click', function(e) {
                const calendar = document.getElementById('availability-calendar');
                const startInput = document.getElementById('start_date');
                const endInput = document.getElementById('end_date');
                const prevButton = document.getElementById('prev-month');
                const nextButton = document.getElementById('next-month');
                
                // Don't hide if clicking on calendar elements or navigation buttons
                if (!calendar.contains(e.target) && 
                    !startInput.contains(e.target) && 
                    !endInput.contains(e.target) &&
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
            
            // Initialize calendar display
            generateCalendar();
        });
    </script>
</x-app-layout>
