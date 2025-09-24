@extends('layouts.admin')

@section('header', 'Vue Calendrier des Réservations')

@section('content')
<div class="p-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Revenus du mois</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($statistics['monthlyRevenue'] ?? 0, 0) }} MAD</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Locations actives</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['active'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En attente</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Terminées</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $statistics['completed'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Calendrier des Réservations</h1>
        <p class="text-gray-600">Visualisez et gérez les réservations sur le calendrier</p>
    </div>

    <!-- Calendar Controls -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="previousMonth()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Précédent
                </button>
                <h2 id="currentMonth" class="text-lg font-semibold text-gray-900">Septembre 2025</h2>
                <button onclick="nextMonth()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Suivant
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <button onclick="goToToday()" 
                        class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-100 border border-blue-300 rounded-md hover:bg-blue-200">
                    Aujourd'hui
                </button>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="setView('month')" 
                        id="monthBtn"
                        class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-md">Mois</button>
                <button onclick="setView('week')" 
                        id="weekBtn"
                        class="px-3 py-1 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-md">Semaine</button>
                <button onclick="setView('day')" 
                        id="dayBtn"
                        class="px-3 py-1 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-md">Jour</button>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Calendrier des Réservations</h3>
        </div>
        
        <!-- Month View -->
        <div id="monthView">
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Lun</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Mar</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Mer</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jeu</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ven</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sam</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Dim</div>
            </div>
            <div id="calendarGrid" class="grid grid-cols-7 gap-px bg-gray-200">
                <!-- Calendar days will be populated by JavaScript -->
            </div>
        </div>

        <!-- Week View -->
        <div id="weekView" class="hidden">
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Lun</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Mar</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Mer</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Jeu</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ven</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sam</div>
                <div class="bg-gray-50 px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Dim</div>
            </div>
            <div id="weekGrid" class="grid grid-cols-7 gap-px bg-gray-200">
                <!-- Week days will be populated by JavaScript -->
            </div>
        </div>

        <!-- Day View -->
        <div id="dayView" class="hidden">
            <div class="grid grid-cols-1 gap-px bg-gray-200">
                <div class="bg-gray-50 px-6 py-4 text-center text-sm font-medium text-gray-500 uppercase" id="dayHeader">
                    <!-- Day header will be populated by JavaScript -->
                </div>
            </div>
            <div id="dayGrid" class="grid grid-cols-1 gap-px bg-gray-200">
                <!-- Day hours will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Légende</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Locations actives</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Réservations en attente</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Locations terminées</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Réservations rejetées</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-100 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Réservations annulées</span>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Détails de la Réservation</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentDate = new Date();
let currentView = 'month';
let bookings = @json($bookings);

const monthNames = [
    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
];

const dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
const dayNamesShort = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
});

function renderCalendar() {
    if (currentView === 'month') {
        renderMonthView();
    } else if (currentView === 'week') {
        renderWeekView();
    } else if (currentView === 'day') {
        renderDayView();
    }
}

function renderMonthView() {
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    
    // Show month view, hide others
    document.getElementById('monthView').classList.remove('hidden');
    document.getElementById('weekView').classList.add('hidden');
    document.getElementById('dayView').classList.add('hidden');
    
    // Update month display
    currentMonthElement.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    
    // Clear calendar
    calendarGrid.innerHTML = '';
    
    // Get first day of month and number of days
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = (firstDay.getDay() + 6) % 7; // Convert Sunday=0 to Monday=0
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'bg-gray-50 min-h-[120px] p-2';
        calendarGrid.appendChild(emptyDay);
    }
    
    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.className = 'bg-white min-h-[120px] p-2 hover:bg-gray-50 transition-colors';
        
        const dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        const dayBookings = getBookingsForDate(dayDate);
        
        const isToday = isSameDay(dayDate, new Date());
        
        dayElement.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium ${isToday ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : 'text-gray-900'}">
                    ${day}
                </span>
                ${dayBookings.length > 0 ? `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        ${dayBookings.length > 2 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                        ${dayBookings.length} réservation${dayBookings.length > 1 ? 's' : ''}
                    </span>
                ` : ''}
            </div>
            
            ${dayBookings.length > 0 ? `
                <div class="space-y-1">
                    ${dayBookings.map(booking => `
                        <div class="text-xs p-1 ${getStatusColor(booking.status)} rounded truncate cursor-pointer hover:opacity-80" 
                             title="${booking.car.brand} ${booking.car.model} - ${booking.user.name} (${getStatusLabel(booking.status)})"
                             onclick="showBookingDetails(${booking.id})">
                            ${booking.car.brand} ${booking.car.model}
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        `;
        
        calendarGrid.appendChild(dayElement);
    }
}

function renderWeekView() {
    const weekGrid = document.getElementById('weekGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    
    // Show week view, hide others
    document.getElementById('monthView').classList.add('hidden');
    document.getElementById('weekView').classList.remove('hidden');
    document.getElementById('dayView').classList.add('hidden');
    
    // Get start of week (Monday)
    const startOfWeek = new Date(currentDate);
    const day = startOfWeek.getDay();
    const diff = startOfWeek.getDate() - day + (day === 0 ? -6 : 1);
    startOfWeek.setDate(diff);
    
    // Update display
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    currentMonthElement.textContent = `Semaine du ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} au ${endOfWeek.getDate()}/${endOfWeek.getMonth() + 1}/${endOfWeek.getFullYear()}`;
    
    // Clear week grid
    weekGrid.innerHTML = '';
    
    // Add days of the week
    for (let i = 0; i < 7; i++) {
        const dayDate = new Date(startOfWeek);
        dayDate.setDate(startOfWeek.getDate() + i);
        const dayBookings = getBookingsForDate(dayDate);
        
        const isToday = isSameDay(dayDate, new Date());
        
        const dayElement = document.createElement('div');
        dayElement.className = 'bg-white min-h-[200px] p-3 hover:bg-gray-50 transition-colors';
        
        dayElement.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <span class="text-lg font-semibold ${isToday ? 'bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center' : 'text-gray-900'}">
                    ${dayDate.getDate()}
                </span>
                ${dayBookings.length > 0 ? `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        ${dayBookings.length > 3 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                        ${dayBookings.length} réservation${dayBookings.length > 1 ? 's' : ''}
                    </span>
                ` : ''}
            </div>
            
            ${dayBookings.length > 0 ? `
                <div class="space-y-2">
                    ${dayBookings.map(booking => `
                        <div class="text-sm p-2 ${getStatusColor(booking.status)} rounded cursor-pointer hover:opacity-80" 
                             title="${booking.car.brand} ${booking.car.model} - ${booking.user.name} (${getStatusLabel(booking.status)})"
                             onclick="showBookingDetails(${booking.id})">
                            <div class="font-medium">${booking.car.brand} ${booking.car.model}</div>
                            <div class="text-xs opacity-75">${booking.user.name}</div>
                        </div>
                    `).join('')}
                </div>
            ` : ''}
        `;
        
        weekGrid.appendChild(dayElement);
    }
}

function renderDayView() {
    const dayGrid = document.getElementById('dayGrid');
    const dayHeader = document.getElementById('dayHeader');
    const currentMonthElement = document.getElementById('currentMonth');
    
    // Show day view, hide others
    document.getElementById('monthView').classList.add('hidden');
    document.getElementById('weekView').classList.add('hidden');
    document.getElementById('dayView').classList.remove('hidden');
    
    // Update display
    const dayName = dayNames[currentDate.getDay()];
    const dayNumber = currentDate.getDate();
    const monthName = monthNames[currentDate.getMonth()];
    const year = currentDate.getFullYear();
    
    currentMonthElement.textContent = `${dayName} ${dayNumber} ${monthName} ${year}`;
    dayHeader.textContent = `${dayName} ${dayNumber} ${monthName} ${year}`;
    
    // Clear day grid
    dayGrid.innerHTML = '';
    
    // Generate hourly slots (8 AM to 8 PM)
    for (let hour = 8; hour <= 20; hour++) {
        const hourDate = new Date(currentDate);
        hourDate.setHours(hour, 0, 0, 0);
        const hourBookings = getBookingsForHour(hourDate);
        
        const isBusinessHours = hour >= 9 && hour <= 18;
        
        const hourElement = document.createElement('div');
        hourElement.className = `bg-white min-h-[60px] p-4 hover:bg-gray-50 transition-colors ${isBusinessHours ? 'bg-blue-50' : ''}`;
        
        hourElement.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-900 w-16">${hour.toString().padStart(2, '0')}:00</span>
                    ${hourBookings.length > 0 ? `
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ${hourBookings.length} réservation${hourBookings.length > 1 ? 's' : ''}
                        </span>
                    ` : ''}
                </div>
                ${hourBookings.length > 0 ? `
                    <div class="flex flex-wrap gap-2">
                        ${hourBookings.map(booking => `
                            <div class="text-xs px-2 py-1 ${getStatusColor(booking.status)} rounded cursor-pointer hover:opacity-80" 
                                 title="${booking.car.brand} ${booking.car.model} - ${booking.user.name} (${getStatusLabel(booking.status)})"
                                 onclick="showBookingDetails(${booking.id})">
                                ${booking.car.brand} ${booking.car.model}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;
        
        dayGrid.appendChild(hourElement);
    }
}

function getBookingsForDate(date) {
    return bookings.filter(booking => {
        const startDate = new Date(booking.start);
        const endDate = new Date(booking.end);
        return date >= startDate && date <= endDate;
    });
}

function getBookingsForHour(hourDate) {
    return bookings.filter(booking => {
        const startDate = new Date(booking.start);
        const endDate = new Date(booking.end);
        // Check if the hour falls within the booking period
        return hourDate >= startDate && hourDate <= endDate;
    });
}

function isSameDay(date1, date2) {
    return date1.getDate() === date2.getDate() &&
           date1.getMonth() === date2.getMonth() &&
           date1.getFullYear() === date2.getFullYear();
}

function getStatusColor(status) {
    switch(status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'completed':
            return 'bg-blue-100 text-blue-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        case 'cancelled':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'En attente',
        'active': 'Active',
        'completed': 'Terminée',
        'rejected': 'Rejetée',
        'cancelled': 'Annulée'
    };
    return labels[status] || status;
}

function previousMonth() {
    if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() - 1);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() - 7);
    } else if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() - 1);
    }
    renderCalendar();
}

function nextMonth() {
    if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + 1);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() + 7);
    } else if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() + 1);
    }
    renderCalendar();
}

function goToToday() {
    currentDate = new Date();
    renderCalendar();
}

function setView(view) {
    currentView = view;
    
    // Update button styles
    document.getElementById('monthBtn').className = view === 'month' ? 
        'px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-md' : 
        'px-3 py-1 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-md';
    
    document.getElementById('weekBtn').className = view === 'week' ? 
        'px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-md' : 
        'px-3 py-1 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-md';
    
    document.getElementById('dayBtn').className = view === 'day' ? 
        'px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-md' : 
        'px-3 py-1 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-md';
    
    // Render the selected view
    renderCalendar();
}

function showBookingDetails(bookingId) {
    const booking = bookings.find(b => b.id == bookingId);
    if (!booking) return;
    
    const modal = document.getElementById('bookingModal');
    const content = document.getElementById('modalContent');
    
    content.innerHTML = `
        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900">Réservation #${booking.id}</h4>
                <p class="text-sm text-gray-600">${booking.car.brand} ${booking.car.model} - ${booking.user.name}</p>
            </div>
            
            <div>
                <h5 class="font-medium text-gray-700 mb-2">Période</h5>
                <p class="text-sm text-gray-600">
                    ${new Date(booking.start).toLocaleDateString('fr-FR')} - ${new Date(booking.end).toLocaleDateString('fr-FR')}
                </p>
            </div>
            
            <div>
                <h5 class="font-medium text-gray-700 mb-2">Statut</h5>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(booking.status)}">
                    ${getStatusLabel(booking.status)}
                </span>
            </div>
            
            <div class="pt-4 border-t">
                <a href="${booking.url}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Voir les détails complets
                </a>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
