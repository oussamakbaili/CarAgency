// Client Notifications System
function clientNotificationsDropdown() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        totalCount: 0,
        loading: false,
        showingAll: false,
        
        init() {
            this.loadNotifications();
            // Refresh notifications every 30 seconds
            setInterval(() => {
                this.loadNotifications();
            }, 30000);
        },
        
        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                this.loadNotifications();
            }
        },
        
        async loadNotifications() {
            if (this.loading) return;
            
            this.loading = true;
            try {
                const limit = this.showingAll ? 50 : 10;
                const response = await fetch(`/client/notifications?limit=${limit}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                    this.totalCount = data.total_count;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async handleNotificationClick(notification) {
            // Mark as read if not already read
            if (!notification.is_read) {
                await this.markAsRead(notification.id);
            }
            
            // Navigate to action URL if exists
            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        },
        
        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/client/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    // Update local notification state
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.is_read = true;
                    }
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch('/client/notifications/read-all', {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    // Update local state
                    this.notifications.forEach(notification => {
                        notification.is_read = true;
                    });
                    this.unreadCount = 0;
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        },
        
        async loadAllNotifications() {
            this.showingAll = true;
            await this.loadNotifications();
        }
    };
}

// Real-time notifications using Server-Sent Events (if available)
if (typeof EventSource !== 'undefined') {
    const eventSource = new EventSource('/client/notifications/stream');
    
    eventSource.onmessage = function(event) {
        const data = JSON.parse(event.data);
        
        // Update notification count
        if (window.Alpine && window.Alpine.store) {
            // If using Alpine store, update it
            const store = window.Alpine.store('notifications');
            if (store) {
                store.unreadCount++;
                store.notifications.unshift(data.notification);
            }
        }
        
        // Show browser notification if permission granted
        if (Notification.permission === 'granted') {
            new Notification(data.notification.title, {
                body: data.notification.message,
                icon: '/favicon.ico'
            });
        }
    };
    
    eventSource.onerror = function(event) {
        console.error('EventSource failed:', event);
    };
}

// Request notification permission
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}
