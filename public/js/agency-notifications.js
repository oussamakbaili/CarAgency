/**
 * Agency Notifications System
 * Real-time notification handling for agency dashboard
 */

class AgencyNotifications {
    constructor() {
        this.notificationQueue = [];
        this.isProcessing = false;
        this.init();
    }

    init() {
        // Check for new notifications every 30 seconds
        this.checkNotifications();
        setInterval(() => this.checkNotifications(), 30000);
        
        // Listen for custom events
        document.addEventListener('agency:notification', (e) => {
            this.showNotification(e.detail);
        });
    }

    async checkNotifications() {
        try {
            // This would typically fetch from an API endpoint
            // For now, we'll check session storage for new notifications
            const pendingNotifications = sessionStorage.getItem('pendingNotifications');
            if (pendingNotifications) {
                const notifications = JSON.parse(pendingNotifications);
                notifications.forEach(notification => {
                    this.addToQueue(notification);
                });
                sessionStorage.removeItem('pendingNotifications');
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }

    addToQueue(notification) {
        this.notificationQueue.push(notification);
        if (!this.isProcessing) {
            this.processQueue();
        }
    }

    async processQueue() {
        if (this.notificationQueue.length === 0) {
            this.isProcessing = false;
            return;
        }

        this.isProcessing = true;
        const notification = this.notificationQueue.shift();
        await this.showNotification(notification);
        
        // Wait 500ms before showing next notification
        setTimeout(() => this.processQueue(), 500);
    }

    async showNotification(notification) {
        return new Promise((resolve) => {
            const {
                type = 'info',
                title = 'Notification',
                message = '',
                duration = 5000,
                action = null
            } = notification;

            // Create notification element
            const notificationEl = document.createElement('div');
            notificationEl.className = 'agency-notification fixed top-4 right-4 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden z-50 transform transition-all duration-300 translate-x-full';
            
            // Get icon and colors based on type
            const config = this.getNotificationConfig(type);
            
            notificationEl.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full ${config.bgColor} flex items-center justify-center">
                                ${config.icon}
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">${title}</p>
                            <p class="mt-1 text-sm text-gray-500">${message}</p>
                            ${action ? `
                                <div class="mt-3">
                                    <button onclick="${action.callback}" class="text-sm font-medium ${config.textColor} hover:${config.hoverColor}">
                                        ${action.label}
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button onclick="this.closest('.agency-notification').remove()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="sr-only">Fermer</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="notification-progress absolute bottom-0 left-0 h-1 ${config.progressColor}" style="width: 100%; transition: width ${duration}ms linear;"></div>
            `;

            document.body.appendChild(notificationEl);

            // Animate in
            setTimeout(() => {
                notificationEl.classList.remove('translate-x-full');
            }, 10);

            // Start progress bar animation
            setTimeout(() => {
                const progressBar = notificationEl.querySelector('.notification-progress');
                if (progressBar) {
                    progressBar.style.width = '0%';
                }
            }, 50);

            // Auto remove
            setTimeout(() => {
                this.removeNotification(notificationEl);
                resolve();
            }, duration);
        });
    }

    removeNotification(element) {
        element.classList.add('translate-x-full');
        setTimeout(() => {
            element.remove();
        }, 300);
    }

    getNotificationConfig(type) {
        const configs = {
            success: {
                bgColor: 'bg-green-100',
                textColor: 'text-green-600',
                hoverColor: 'text-green-700',
                progressColor: 'bg-green-500',
                icon: `<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`
            },
            error: {
                bgColor: 'bg-red-100',
                textColor: 'text-red-600',
                hoverColor: 'text-red-700',
                progressColor: 'bg-red-500',
                icon: `<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`
            },
            warning: {
                bgColor: 'bg-yellow-100',
                textColor: 'text-yellow-600',
                hoverColor: 'text-yellow-700',
                progressColor: 'bg-yellow-500',
                icon: `<svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>`
            },
            info: {
                bgColor: 'bg-blue-100',
                textColor: 'text-blue-600',
                hoverColor: 'text-blue-700',
                progressColor: 'bg-blue-500',
                icon: `<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`
            }
        };

        return configs[type] || configs.info;
    }

    // Public methods to trigger notifications
    success(title, message, action = null) {
        this.addToQueue({ type: 'success', title, message, action });
    }

    error(title, message, action = null) {
        this.addToQueue({ type: 'error', title, message, action });
    }

    warning(title, message, action = null) {
        this.addToQueue({ type: 'warning', title, message, action });
    }

    info(title, message, action = null) {
        this.addToQueue({ type: 'info', title, message, action });
    }
}

// Initialize on page load
let agencyNotifications;
document.addEventListener('DOMContentLoaded', () => {
    agencyNotifications = new AgencyNotifications();
    
    // Make it globally accessible
    window.agencyNotifications = agencyNotifications;
    
    // Check for Laravel session messages
    const successMessage = document.querySelector('[data-success-message]');
    if (successMessage) {
        agencyNotifications.success(
            'Succès',
            successMessage.getAttribute('data-success-message')
        );
    }
    
    const errorMessage = document.querySelector('[data-error-message]');
    if (errorMessage) {
        agencyNotifications.error(
            'Erreur',
            errorMessage.getAttribute('data-error-message')
        );
    }
});

