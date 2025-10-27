// Global Event Handlers - Prevents Double-Click Issues
// This file ensures events are handled properly and prevents conflicts

(function() {
    'use strict';
    
    // Prevent multiple event listeners from being attached
    const attachedListeners = new Set();
    
    // Safe event listener attachment
    function safeAddEventListener(element, event, handler, options = {}) {
        const key = `${element}_${event}_${handler.name || 'anonymous'}`;
        
        if (attachedListeners.has(key)) {
            return; // Already attached
        }
        
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        
        if (!element) {
            console.warn('Element not found for event listener:', element);
            return;
        }
        
        element.addEventListener(event, handler, options);
        attachedListeners.add(key);
    }
    
    // Safe DOM ready handler
    function safeDOMReady(handler) {
        const key = `domready_${handler.name || 'anonymous'}`;
        
        if (attachedListeners.has(key)) {
            return; // Already attached
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', handler);
        } else {
            // DOM already ready
            handler();
        }
        
        attachedListeners.add(key);
    }
    
    // Global navigation handler to prevent double-click issues
    function handleNavigation(event) {
        const target = event.target.closest('a[href], button[onclick]');
        
        if (!target) return;
        
        // Prevent rapid successive clicks
        if (target.dataset.navigating === 'true') {
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
        
        // Mark as navigating
        target.dataset.navigating = 'true';
        
        // Reset after a short delay
        setTimeout(() => {
            target.dataset.navigating = 'false';
        }, 1000);
    }
    
    // Apply global navigation handler
    safeDOMReady(function() {
        // Add click handler to prevent double-click issues
        document.addEventListener('click', handleNavigation, true);
        
        // Handle form submissions
        document.addEventListener('submit', function(event) {
            const form = event.target;
            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return false;
            }
            form.dataset.submitting = 'true';
            
            setTimeout(() => {
                form.dataset.submitting = 'false';
            }, 2000);
        });
    });
    
    // Export functions for global use
    window.safeAddEventListener = safeAddEventListener;
    window.safeDOMReady = safeDOMReady;
    
})();
