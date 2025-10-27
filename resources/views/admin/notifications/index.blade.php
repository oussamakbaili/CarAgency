@extends('layouts.admin')

@section('title', 'Notifications')

@section('header', 'Notifications')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-center gap-4">
            <div>
                <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select id="category-filter" onchange="filterNotifications()" class="border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="all">Toutes les catégories</option>
                    <option value="support">Support</option>
                    <option value="reservation">Réservations</option>
                    <option value="payment">Paiements</option>
                    <option value="agency">Agences</option>
                </select>
            </div>
            
            <div>
                <label for="read-filter" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="read-filter" onchange="filterNotifications()" class="border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="all">Toutes</option>
                    <option value="unread">Non lues</option>
                    <option value="read">Lues</option>
                </select>
            </div>
            
            <div class="flex-1"></div>
            
            <div class="flex items-center space-x-2">
                <button onclick="markAllAsRead()" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Marquer tout comme lu
                </button>
                <button onclick="clearAllNotifications()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Effacer tout
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Liste des notifications</h2>
        </div>
        
        <div id="notifications-container" class="divide-y divide-gray-200">
            <!-- Notifications will be loaded here -->
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p>Chargement des notifications...</p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total</p>
                    <p id="total-notifications" class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Non lues</p>
                    <p id="unread-notifications" class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Support</p>
                    <p id="support-notifications" class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Paiements</p>
                    <p id="payment-notifications" class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    loadStats();
    
    // Auto-refresh every 30 seconds
    setInterval(loadNotifications, 30000);
    setInterval(loadStats, 30000);
});

// Load notifications with filters
async function loadNotifications() {
    const category = document.getElementById('category-filter').value;
    const read = document.getElementById('read-filter').value;
    
    try {
        const response = await fetch(`/admin/notifications?category=${category}&read=${read}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayNotifications(data.notifications);
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

// Load statistics
async function loadStats() {
    try {
        const response = await fetch('/admin/notifications/stats');
        const data = await response.json();
        
        document.getElementById('total-notifications').textContent = data.total;
        document.getElementById('unread-notifications').textContent = data.unread;
        document.getElementById('support-notifications').textContent = data.by_category.support;
        document.getElementById('payment-notifications').textContent = data.by_category.payment;
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Display notifications
function displayNotifications(notifications) {
    const container = document.getElementById('notifications-container');
    
    if (notifications.length === 0) {
        container.innerHTML = `
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p>Aucune notification trouvée</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = notifications.map(notification => createNotificationElement(notification)).join('');
}

// Create notification element
function createNotificationElement(notification) {
    const isUnread = !notification.is_read;
    const timeAgo = new Date(notification.created_at).toLocaleString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    return `
        <div class="p-6 hover:bg-gray-50 ${isUnread ? 'bg-orange-50' : ''}">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full ${notification.icon_color_class} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${notification.icon_svg}"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-semibold text-gray-900">${notification.title}</p>
                            <p class="text-sm text-gray-500">${notification.category} • ${notification.priority}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <p class="text-sm text-gray-500">${timeAgo}</p>
                            ${isUnread ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Non lue</span>' : ''}
                        </div>
                    </div>
                    <p class="text-gray-600 mt-2">${notification.message}</p>
                    ${notification.data && notification.data.sender_name ? `
                        <p class="text-sm text-gray-500 mt-2">De: ${notification.data.sender_name}</p>
                    ` : ''}
                    ${notification.action_url ? `
                        <div class="mt-4">
                            <a href="${notification.action_url}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-orange-700 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Voir les détails
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    ` : ''}
                </div>
                <div class="flex items-center space-x-2">
                    ${isUnread ? `
                        <button onclick="markNotificationAsRead(${notification.id})" class="p-2 text-gray-400 hover:text-orange-600 transition-colors" title="Marquer comme lu">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    ` : ''}
                    <button onclick="deleteNotification(${notification.id})" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Supprimer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Filter notifications
function filterNotifications() {
    loadNotifications();
}

// Mark notification as read
async function markNotificationAsRead(notificationId) {
    try {
        await fetch(`/admin/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        loadNotifications();
        loadStats();
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

// Delete notification
async function deleteNotification(notificationId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
        return;
    }
    
    try {
        await fetch(`/admin/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        loadNotifications();
        loadStats();
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
}

// Mark all notifications as read
async function markAllAsRead() {
    try {
        await fetch('/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        loadNotifications();
        loadStats();
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Clear all notifications
async function clearAllNotifications() {
    if (!confirm('Êtes-vous sûr de vouloir supprimer toutes les notifications ?')) {
        return;
    }
    
    try {
        await fetch('/admin/notifications/clear-all', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        loadNotifications();
        loadStats();
    } catch (error) {
        console.error('Error clearing all notifications:', error);
    }
}
</script>
@endpush
@endsection
