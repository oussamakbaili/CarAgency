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
        
        // Skip search bar and modal elements - they have their own handlers
        if (target.id === 'mobileSearchBar' || 
            target.closest('#mobileSearchBar') || 
            target.closest('#searchModal') ||
            target.closest('.hero-search')) {
            console.log('🔍 Search bar/modal - skipping navigation fix');
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
                
                // Intercepter les changements de location via un proxy sur location.href
                let locationChanged = false;
                const originalLocationHref = Object.getOwnPropertyDescriptor(window.location, 'href') || 
                                            Object.getOwnPropertyDescriptor(Object.getPrototypeOf(window.location), 'href');
                
                // Vérifier si on peut redéfinir location.href
                try {
                    // Intercepter location.href au lieu de location
                    const locationHrefDescriptor = Object.getOwnPropertyDescriptor(window.location, 'href');
                    if (locationHrefDescriptor && locationHrefDescriptor.configurable) {
                        Object.defineProperty(window.location, 'href', {
                            get: function() {
                                return originalLocationHref ? originalLocationHref.get.call(this) : window.location.href;
                            },
                            set: function(url) {
                                console.log('📍 Location changée vers:', url);
                                destinationUrl = url;
                                locationChanged = true;
                                if (originalLocationHref && originalLocationHref.set) {
                                    originalLocationHref.set.call(window.location, url);
                                } else {
                                    window.location.href = url;
                                }
                            },
                            configurable: true
                        });
                    }
                } catch (e) {
                    console.log('⚠️ Impossible de redéfinir location.href, utilisation alternative');
                }
                
                // Exécuter le onclick
                if (typeof target.onclick === 'function') {
                    target.onclick(event);
                } else if (typeof target.onclick === 'object' && target.onclick.handleEvent) {
                    target.onclick.handleEvent(event);
                }
                
                // Restaurer location.href si nécessaire
                if (locationChanged && originalLocationHref && originalLocationHref.configurable) {
                    try {
                        Object.defineProperty(window.location, 'href', originalLocationHref);
                    } catch (e) {
                        // Ignorer si on ne peut pas restaurer
                    }
                }
                
                if (locationChanged && destinationUrl) {
                    console.log('✅ Navigation capturée:', destinationUrl);
                }
                
            } catch (error) {
                console.error('❌ Erreur lors de l\'exécution du onclick:', error);
                // Si l'onclick ne fonctionne pas, essayer d'exécuter directement
                if (target.onclick && typeof target.onclick === 'function') {
                    try {
                        target.onclick.call(target, event);
                    } catch (e2) {
                        console.error('❌ Erreur lors de l\'exécution directe:', e2);
                    }
                }
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
