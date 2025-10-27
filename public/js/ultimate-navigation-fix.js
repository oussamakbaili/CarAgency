// SOLUTION ULTIME POUR CORRIGER LE PROBLÈME DE DOUBLE-CLIC
// Ce script force la navigation de manière absolue

(function() {
    'use strict';
    
    console.log('🚀 DÉMARRAGE DE LA SOLUTION ULTIME');
    
    // Force la navigation immédiate
    function ultimateNavigation(event) {
        console.log('🎯 ULTIMATE NAVIGATION DÉCLENCHÉE');
        
        const target = event.target.closest('a[href], button[onclick], [onclick]');
        
        if (!target) {
            console.log('❌ Aucune cible de navigation trouvée');
            return;
        }
        
        // Skip message conversation items - they have their own click handlers
        if (target.classList.contains('conversation-item') || target.closest('.conversation-item')) {
            console.log('📱 Message conversation item - skipping navigation fix');
            return;
        }
        
        console.log('✅ Cible trouvée:', target);
        
        // Empêcher TOUS les autres gestionnaires
        event.stopImmediatePropagation();
        event.preventDefault();
        
        // Marquer comme en cours de traitement
        target.dataset.processing = 'true';
        target.style.pointerEvents = 'none';
        
        // Déterminer l'URL de destination
        let destinationUrl = null;
        
        if (target.tagName === 'A' && target.href) {
            destinationUrl = target.href;
            console.log('🔗 Lien détecté:', destinationUrl);
        } else if (target.onclick) {
            // Exécuter le onclick et capturer la navigation
            console.log('🔘 Bouton avec onclick détecté');
            try {
                // Sauvegarder window.location.href
                const originalHref = window.location.href;
                
                // Intercepter les changements de location
                let locationChanged = false;
                const originalLocation = window.location;
                
                // Créer un proxy pour window.location
                Object.defineProperty(window, 'location', {
                    get: function() {
                        return originalLocation;
                    },
                    set: function(url) {
                        console.log('📍 Location changée vers:', url);
                        destinationUrl = url;
                        locationChanged = true;
                        originalLocation.href = url;
                    }
                });
                
                // Exécuter le onclick
                target.onclick(event);
                
                // Restaurer window.location
                Object.defineProperty(window, 'location', {
                    get: function() {
                        return originalLocation;
                    },
                    set: function(url) {
                        originalLocation.href = url;
                    }
                });
                
                if (locationChanged && destinationUrl) {
                    console.log('✅ Navigation capturée:', destinationUrl);
                }
                
            } catch (error) {
                console.error('❌ Erreur lors de l\'exécution du onclick:', error);
            }
        }
        
        // Si on a une URL, naviguer immédiatement
        if (destinationUrl) {
            console.log('🚀 Navigation vers:', destinationUrl);
            
            // Navigation immédiate
            setTimeout(() => {
                window.location.href = destinationUrl;
            }, 10);
            
            return false;
        }
        
        // Réactiver l'élément après un délai
        setTimeout(() => {
            target.dataset.processing = 'false';
            target.style.pointerEvents = 'auto';
        }, 1000);
    }
    
    // Intercepter TOUS les clics avec la priorité maximale
    document.addEventListener('click', ultimateNavigation, true);
    
    // Intercepter aussi les événements touch pour mobile
    document.addEventListener('touchstart', function(event) {
        const target = event.target.closest('a[href], button[onclick], [onclick]');
        if (target) {
            console.log('📱 Touch détecté sur:', target);
            // Convertir touch en click
            setTimeout(() => {
                const clickEvent = new MouseEvent('click', {
                    bubbles: true,
                    cancelable: true,
                    view: window
                });
                target.dispatchEvent(clickEvent);
            }, 50);
        }
    }, true);
    
    // Forcer le style des éléments cliquables
    function styleClickableElements() {
        const clickableElements = document.querySelectorAll('a[href], button, [onclick], .cursor-pointer, [role="button"]');
        
        clickableElements.forEach(element => {
            element.style.cursor = 'pointer';
            element.style.userSelect = 'none';
            element.style.webkitUserSelect = 'none';
            
            // Ajouter un indicateur visuel
            if (!element.dataset.ultimateFix) {
                element.dataset.ultimateFix = 'true';
                
                element.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
                });
                
                element.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            }
        });
        
        console.log(`✅ ${clickableElements.length} éléments cliquables stylisés`);
    }
    
    // Appliquer les styles
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', styleClickableElements);
    } else {
        styleClickableElements();
    }
    
    // Réappliquer les styles périodiquement
    setInterval(styleClickableElements, 2000);
    
    console.log('✅ SOLUTION ULTIME CHARGÉE');
    
    // Fonction de diagnostic
    window.diagnoseNavigation = function() {
        console.log('🔍 DIAGNOSTIC DE NAVIGATION:');
        console.log('- Scripts chargés:', document.querySelectorAll('script[src]').length);
        console.log('- Liens détectés:', document.querySelectorAll('a[href]').length);
        console.log('- Boutons détectés:', document.querySelectorAll('button').length);
        console.log('- Éléments avec onclick:', document.querySelectorAll('[onclick]').length);
        console.log('- Alpine.js disponible:', !!window.Alpine);
        console.log('- jQuery disponible:', !!window.jQuery);
        
        // Tester un clic simulé
        const testLink = document.querySelector('a[href]');
        if (testLink) {
            console.log('🧪 Test de clic sur:', testLink.href);
            testLink.click();
        }
    };
    
    console.log('💡 Utilisez diagnoseNavigation() pour diagnostiquer');
    
})();
